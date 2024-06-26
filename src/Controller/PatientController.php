<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Personnel;
use App\Entity\User;
use App\Form\PatientType;
use App\Form\AssignerType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends AbstractController
{
    #[Route('/patients', name: 'patients')]
    public function patients(EntityManagerInterface $entityManager): Response
    {
        $patients = $entityManager->getRepository(Patient::class)->findAll();

        return $this->render('admin/patients.html.twig', ['patients' => $patients]);
    }

    #[Route('/mes_patients', name: 'mes_patients')]
    public function mes_patients(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $personnel = $entityManager->getRepository(Personnel::class)->findBy(["email" => $session->all()["_security.last_username"]]);

        $patients = $entityManager->getRepository(Patient::class)->findBy(["personnel" => $personnel[0]]);

        return $this->render('personnel/patients.html.twig', ['patients' => $patients]);
    }

    #[Route('/nos_patients', name: 'nos_patients')]
    public function nos_patients(EntityManagerInterface $entityManager): Response
    {
        $patients = $entityManager->getRepository(Patient::class)->findAll();

        return $this->render('personnel/patients.html.twig', ['patients' => $patients]);
    }

    #[Route('/editer_patient/{id?0}', name: 'editer_patient')]
    public function editer_patient(Patient $patient = null, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $new = false;
        if(!$patient){
            $new = true;
            $patient = new Patient();
        }

        $form = $this->createForm(PatientType::class, $patient);

        //dd($request->request);
        $form->handleRequest($request);

        if($form->isSubmitted()){

            $userVer = $entityManager->getRepository(User::class)->findBy(['email' => $form->get('email')->getData()]);

            if(empty($userVer)){
                $user = new User();
            }else{
                $user = $userVer[0];
            }

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setEmail($form->get('email')->getData());
            $user->setRole("Patient");
            $user->setPseudo($form->get('prenom_patient')->getData());

            $dateD = new \DateTime();
            $patient->setPassword($user->getPassword());
            $patient->setDateCreation($dateD);

            $manager = $doctrine->getManager();
            $manager->persist($patient);
            $manager->persist($user);

            $manager->flush();

            if($new){
                $message = "Le patient a été ajouter avec succès";
            }else{
                $message = "Le patient a été editer avec succès";
            }

            $this->addFlash("succes", $message);

            return $this->redirectToRoute("patients");
        }else{
            return $this->render('admin/add_patient.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/assigner_medecin', name: 'assigner_medecin')]
    public function assigner_medecin(Patient $patient = null, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(AssignerType::class, $patient);

        //dd($request->request);
        $form->handleRequest($request);

        if($form->isSubmitted()){

            $userVer = $entityManager->getRepository(Patient::class)->findBy(['matricule' => $form->get('matricule')->getData()]);

            if(empty($userVer)){
                return $this->redirectToRoute("editer_patient");
            }else{
                $user = $userVer[0];
                $user->setPersonnel($form->get('personnel')->getData());
                $manager = $doctrine->getManager();
                $manager->persist($user);

                $manager->flush();
                return $this->redirectToRoute("patients");
            }
        }else{
            return $this->render('admin/assigner.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/detail_patient/{id<\d+>}', name: 'detail_patient')]
    public function detail_patient(ManagerRegistry $doctrine, Patient $patient= null, $id): Response
    {
        if(!$patient){
            $this->addFlash('error', "Ce patient n'existe pas !");
            return $this->redirectToRoute("patients");
        }
        return $this->render('admin/details_patient.html.twig', ['patient' => $patient]);
    }

    #[Route('/patient_detail/{id<\d+>}', name: 'patient_detail')]
    public function patient_detail(ManagerRegistry $doctrine, Patient $patient= null, $id): Response
    {
        if(!$patient){
            $this->addFlash('error', "Ce patient n'existe pas !");
            return $this->redirectToRoute("patients");
        }
        return $this->render('personnel/details_patient.html.twig', ['patient' => $patient]);
    }

    #[Route('/delete_patient/{id?0}', name: 'delete_patient')]
    public function delete_patient(Patient $patient = null, ManagerRegistry $doctrine, Request $request, $id): Response
    {

        $repository = $doctrine->getRepository(Patient::class);
        $patient = $repository->find($id);

        $manager = $doctrine->getManager();
        $manager->remove($patient);

        $manager->flush();

        $message = "Le patient a été supprimer avec succès";


        $this->addFlash("succes", $message);

        return $this->redirectToRoute("patients");

    }

}
