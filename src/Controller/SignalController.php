<?php

namespace App\Controller;

use App\Entity\Signalement;
use App\Form\SignalType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SignalController extends AbstractController
{
    #[Route('/signals', name: 'signals')]
    public function signals(EntityManagerInterface $entityManager): Response
    {
        $signals = $entityManager->getRepository(Signalement::class)->findAll();

        return $this->render('admin/signals.html.twig', ['signals' => $signals]);
    }

    #[Route('/mes_signals', name: 'mes_signals')]
    public function mes_signals(EntityManagerInterface $entityManager): Response
    {
        $signals = $entityManager->getRepository(Signalement::class)->findAll();

        return $this->render('patient/signals.html.twig', ['signals' => $signals]);
    }

    #[Route('/editer_signal/{id?0}', name: 'editer_signal')]
    public function editer_signal(Signalement $signal = null, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $new = false;
        if(!$signal){
            $new = true;
            $signal = new Signalement();
        }

        $form = $this->createForm(SignalType::class, $signal);

        //dd($request->request);
        $form->handleRequest($request);

        if($form->isSubmitted()){

            $dateD = new \DateTime();
            $signal->setDateSignalement($dateD);

            $manager = $doctrine->getManager();
            $manager->persist($signal);

            $manager->flush();

            if($new){
                $message = "Le signal a été ajouter avec succès";
            }else{
                $message = "Le signal a été editer avec succès";
            }

            $this->addFlash("succes", $message);

            return $this->redirectToRoute("signals");
        }else{
            return $this->render('patient/add_signal.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/detail_signal/{id<\d+>}', name: 'detail_signal')]
    public function detail_signal(ManagerRegistry $doctrine, Signalement $signal= null, $id): Response
    {
        if(!$signal){
            $this->addFlash('error', "Ce signal n'existe pas !");
            return $this->redirectToRoute("signals");
        }
        return $this->render('admin/signal_details.html.twig', ['signal' => $signal]);
    }

    #[Route('/signal_detail/{id<\d+>}', name: 'signal_detail')]
    public function signal_detail(ManagerRegistry $doctrine, Signalement $signal= null, $id): Response
    {
        if(!$signal){
            $this->addFlash('error', "Ce signal n'existe pas !");
            return $this->redirectToRoute("signals");
        }
        return $this->render('admin/signal_details.html.twig', ['signal' => $signal]);
    }

    #[Route('/delete_signal/{id?0}', name: 'delete_signal')]
    public function delete_signal(Signalement $signal = null, ManagerRegistry $doctrine, Request $request, $id): Response
    {

        $repository = $doctrine->getRepository(Signalement::class);
        $signal = $repository->find($id);

        $manager = $doctrine->getManager();
        $manager->remove($signal);

        $manager->flush();

        $message = "Le signal a été supprimer avec succès";


        $this->addFlash("succes", $message);

        return $this->redirectToRoute("signals");

    }

}
