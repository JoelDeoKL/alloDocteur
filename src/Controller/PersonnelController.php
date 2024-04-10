<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Personnel;
use App\Entity\User;
use App\Form\PersonnelType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DomCrawler\add;

class PersonnelController extends AbstractController
{
    #[Route('/personnels', name: 'personnels')]
    public function personnels(EntityManagerInterface $entityManager): Response
    {
        $personnels = $entityManager->getRepository(Personnel::class)->findAll();

        return $this->render('admin/personnels.html.twig', ['personnels' => $personnels]);
    }

    #[Route('/les_personnels', name: 'les_personnels')]
    public function les_personnels(EntityManagerInterface $entityManager): Response
    {
        $personnels = $entityManager->getRepository(Personnel::class)->findAll();

        return $this->render('patient/personnels.html.twig', ['personnels' => $personnels]);
    }

    #[Route('/nos_personnels', name: 'nos_personnels')]
    public function nos_personnels(EntityManagerInterface $entityManager): Response
    {
        $personnels = $entityManager->getRepository(Personnel::class)->findAll();

        return $this->render('personnel/personnels.html.twig', ['personnels' => $personnels]);
    }

    #[Route('/mon_medecin', name: 'mon_medecin')]
    public function mon_medecin(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $patient = $entityManager->getRepository(Patient::class)->findBy(["email" => $session->all()["_security.last_username"]]);

        $personnel = $entityManager->getRepository(Personnel::class)->findBy(["id" => $patient[0]->getPersonnel()->getId()]);

        return $this->render('patient/personnel.html.twig', ['personnel' => $personnel]);
    }

    #[Route('/editer_personnel/{id?0}', name: 'editer_personnel')]
    public function editer_personnel(Personnel $personnel = null, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $new = false;
        if(!$personnel){
            $new = true;
            $personnel = new Personnel();
        }

        $form = $this->createForm(PersonnelType::class, $personnel);

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
            $user->setRole("Medecin");
            $user->setPseudo($form->get('prenom_personnel')->getData());

            $dateD = new \DateTime();
            $personnel->setPassword($user->getPassword());
            $personnel->setDateCreation($dateD);

            $manager = $doctrine->getManager();
            $manager->persist($personnel);
            $manager->persist($user);

            $manager->flush();

            if($new){
                $message = "Le personnel a été ajouter avec succès";
            }else{
                $message = "Le personnel a été editer avec succès";
            }

            $this->addFlash("succes", $message);

            return $this->redirectToRoute("personnels");
        }else{
            return $this->render('admin/add_personnel.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/detail_personnel/{id<\d+>}', name: 'detail_personnel')]
    public function detail_personnel(ManagerRegistry $doctrine, Personnel $personnel= null, $id): Response
    {
        if(!$personnel){
            $this->addFlash('error', "Ce personnel n'existe pas !");
            return $this->redirectToRoute("personnels");
        }
        return $this->render('admin/details_personnel.html.twig', ['personnel' => $personnel]);
    }

    #[Route('/personnel_detail/{id<\d+>}', name: 'personnel_detail')]
    public function personnel_detail(ManagerRegistry $doctrine, Personnel $personnel= null, $id): Response
    {
        if(!$personnel){
            $this->addFlash('error', "Ce personnel n'existe pas !");
            return $this->redirectToRoute("personnels");
        }
        return $this->render('personnel/details_personnel.html.twig', ['personnel' => $personnel]);
    }

    #[Route('/delete_personnel/{id?0}', name: 'delete_personnel')]
    public function delete_personnel(Personnel $personnel = null, ManagerRegistry $doctrine, Request $request, $id): Response
    {

        $repository = $doctrine->getRepository(Personnel::class);
        $personnel = $repository->find($id);

        $manager = $doctrine->getManager();
        $manager->remove($personnel);

        $manager->flush();

        $message = "Le personnel a été supprimer avec succès";


        $this->addFlash("succes", $message);

        return $this->redirectToRoute("personnels");

    }

}
