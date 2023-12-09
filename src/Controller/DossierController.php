<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\Patient;
use App\Entity\User;
use App\Form\DossierType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DossierController extends AbstractController
{
    #[Route('/dossiers', name: 'dossiers')]
    public function dossiers(EntityManagerInterface $entityManager): Response
    {
        $dossiers = $entityManager->getRepository(Dossier::class)->findAll();

        return $this->render('admin/dossiers.html.twig', ['dossiers' => $dossiers]);
    }

    #[Route('/mon_dossier', name: 'mon_dossier')]
    public function mon_dossier(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $patient = $entityManager->getRepository(Patient::class)->findBy(["email" => $session->all()["_security.last_username"]]);

        $dossiers = $entityManager->getRepository(Dossier::class)->findBy(["patient" => $patient[0]]);

        return $this->render('patient/dossiers.html.twig', ['dossiers' => $dossiers]);
    }

    #[Route('/editer_dossier/{id?0}', name: 'editer_dossier')]
    public function editer_dossier(Dossier $dossier = null, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $new = false;
        if(!$dossier){
            $new = true;
            $dossier = new Dossier();
        }

        $form = $this->createForm(DossierType::class, $dossier);

        //dd($request->request);
        $form->handleRequest($request);

        if($form->isSubmitted()){

            $dateD = new \DateTime();
            $dossier->setDateCreation($dateD);

            $manager = $doctrine->getManager();
            $manager->persist($dossier);

            $manager->flush();

            if($new){
                $message = "Le dossier a été ajouter avec succès";
            }else{
                $message = "Le dossier a été editer avec succès";
            }

            $this->addFlash("succes", $message);

            return $this->redirectToRoute("dossiers");
        }else{
            return $this->render('admin/add_dossier.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/detail_dossier/{id<\d+>}', name: 'detail_dossier')]
    public function detail_dossier(ManagerRegistry $doctrine, Dossier $dossier= null, $id): Response
    {
        if(!$dossier){
            $this->addFlash('error', "Ce dossier n'existe pas !");
            return $this->redirectToRoute("dossiers");
        }
        return $this->render('admin/dossier_details.html.twig', ['dossier' => $dossier]);
    }

    #[Route('/delete_dossier/{id?0}', name: 'delete_dossier')]
    public function delete_dossier(Dossier $dossier = null, ManagerRegistry $doctrine, Request $request, $id): Response
    {

        $repository = $doctrine->getRepository(Dossier::class);
        $dossier = $repository->find($id);

        $manager = $doctrine->getManager();
        $manager->remove($dossier);

        $manager->flush();

        $message = "Le dossier a été supprimer avec succès";


        $this->addFlash("succes", $message);

        return $this->redirectToRoute("dossiers");

    }

}
