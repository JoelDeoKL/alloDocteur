<?php

namespace App\Controller;

use App\Entity\Fiche;
use App\Entity\Patient;
use App\Form\FicheType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class FicheController extends AbstractController
{
    #[Route('/fiches', name: 'fiches')]
    public function fiches(EntityManagerInterface $entityManager): Response
    {
        $fiches = $entityManager->getRepository(Fiche::class)->findAll();

        return $this->render('admin/fiches.html.twig', ['fiches' => $fiches]);
    }

    #[Route('/nos_fiches', name: 'nos_fiches')]
    public function nos_fiches(EntityManagerInterface $entityManager): Response
    {
        $fiches = $entityManager->getRepository(Fiche::class)->findAll();

        return $this->render('personnel/fiches.html.twig', ['fiches' => $fiches]);
    }

    #[Route('/mes_fiches', name: 'mes_fiches')]
    public function mes_fiches(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $patient = $entityManager->getRepository(Patient::class)->findBy(["email" => $session->all()["_security.last_username"]]);

        $fiches = $entityManager->getRepository(Fiche::class)->findBy(["patient" => $patient[0]]);

        return $this->render('patient/fiches.html.twig', ['fiches' => $fiches]);
    }

    #[Route('/editer_fiche/{id?0}', name: 'editer_fiche')]
    public function editer_fiche(Fiche $fiche = null, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $new = false;
        if(!$fiche){
            $new = true;
            $fiche = new Fiche();
        }

        $form = $this->createForm(FicheType::class, $fiche);

        //dd($request->request);
        $form->handleRequest($request);

        if($form->isSubmitted()){

            $dateD = new \DateTime();
            $fiche->setDateCreation($dateD);

            $manager = $doctrine->getManager();
            $manager->persist($fiche);

            $manager->flush();

            if($new){
                $message = "Le fiche a été ajouter avec succès";
            }else{
                $message = "Le fiche a été editer avec succès";
            }

            $this->addFlash("succes", $message);

            return $this->redirectToRoute("fiches");
        }else{
            return $this->render('admin/add_fiche.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/detail_fiche/{id<\d+>}', name: 'detail_fiche')]
    public function detail_fiche(ManagerRegistry $doctrine, Fiche $fiche= null, $id): Response
    {
        if(!$fiche){
            $this->addFlash('error', "Ce fiche n'existe pas !");
            return $this->redirectToRoute("fiches");
        }
        return $this->render('admin/details_fiche.html.twig', ['fiche' => $fiche]);
    }

    #[Route('/fiche_detail/{id<\d+>}', name: 'fiche_detail')]
    public function fiche_detail(ManagerRegistry $doctrine, Fiche $fiche= null, $id): Response
    {
        if(!$fiche){
            $this->addFlash('error', "Ce fiche n'existe pas !");
            return $this->redirectToRoute("fiches");
        }
        return $this->render('personnel/details_fiche.html.twig', ['fiche' => $fiche]);
    }

    #[Route('/delete_fiche/{id?0}', name: 'delete_fiche')]
    public function delete_fiche(Fiche $fiche = null, ManagerRegistry $doctrine, Request $request, $id): Response
    {

        $repository = $doctrine->getRepository(Fiche::class);
        $fiche = $repository->find($id);

        $manager = $doctrine->getManager();
        $manager->remove($fiche);

        $manager->flush();

        $message = "Le fiche a été supprimer avec succès";


        $this->addFlash("succes", $message);

        return $this->redirectToRoute("fiches");

    }

}
