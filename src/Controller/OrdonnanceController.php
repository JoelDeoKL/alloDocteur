<?php

namespace App\Controller;

use App\Entity\Ordonnance;
use App\Entity\Patient;
use App\Entity\Personnel;
use App\Form\OrdonnanceType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrdonnanceController extends AbstractController
{
    #[Route('/ordonnances', name: 'ordonnances')]
    public function ordonnances(EntityManagerInterface $entityManager): Response
    {
        $ordonnances = $entityManager->getRepository(Ordonnance::class)->findAll();

        return $this->render('admin/ordonnances.html.twig', ['ordonnances' => $ordonnances]);
    }

    #[Route('/mes_ordonnances', name: 'mes_ordonnances')]
    public function mes_ordonnances(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $patient = $entityManager->getRepository(Patient::class)->findBy(["email" => $session->all()["_security.last_username"]]);

        $ordonnances = $entityManager->getRepository(Ordonnance::class)->findBy(["patient" => $patient[0]]);

        return $this->render('patient/ordonnances.html.twig', ['ordonnances' => $ordonnances]);
    }

    #[Route('/nos_ordonnances', name: 'nos_ordonnances')]
    public function nos_ordonnances(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $personnel = $entityManager->getRepository(Personnel::class)->findBy(["email" => $session->all()["_security.last_username"]]);

        $ordonnances = $entityManager->getRepository(Ordonnance::class)->findBy(["personnel" => $personnel[0]]);

        return $this->render('personnel/ordonnances.html.twig', ['ordonnances' => $ordonnances]);
    }

    #[Route('/editer_ordonnance/{id?0}', name: 'editer_ordonnance')]
    public function editer_ordonnance(Ordonnance $ordonnance = null, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $new = false;
        if(!$ordonnance){
            $new = true;
            $ordonnance = new Ordonnance();
        }

        $form = $this->createForm(OrdonnanceType::class, $ordonnance);

        //dd($request->request);
        $form->handleRequest($request);

        if($form->isSubmitted()){

            $dateD = new \DateTime();
            $ordonnance->setDateOrdonnance($dateD);

            $manager = $doctrine->getManager();
            $manager->persist($ordonnance);

            $manager->flush();

            if($new){
                $message = "Le ordonnance a été ajouter avec succès";
            }else{
                $message = "Le ordonnance a été editer avec succès";
            }

            $this->addFlash("succes", $message);

            return $this->redirectToRoute("ordonnances");
        }else{
            return $this->render('personnel/add_ordonnance.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/detail_ordonnance/{id<\d+>}', name: 'detail_ordonnance')]
    public function detail_ordonnance(ManagerRegistry $doctrine, Ordonnance $ordonnance= null, $id): Response
    {
        if(!$ordonnance){
            $this->addFlash('error', "Ce ordonnance n'existe pas !");
            return $this->redirectToRoute("ordonnances");
        }
        return $this->render('admin/details_ordonnance.html.twig', ['ordannance' => $ordonnance]);
    }

    #[Route('/ordonnance_detail/{id<\d+>}', name: 'ordonnance_detail')]
    public function ordonnance_detail(ManagerRegistry $doctrine, Ordonnance $ordonnance= null, $id): Response
    {
        if(!$ordonnance){
            $this->addFlash('error', "Ce ordonnance n'existe pas !");
            return $this->redirectToRoute("ordonnances");
        }
        return $this->render('personnel/details_ordonnance.html.twig', ['ordannance' => $ordonnance]);
    }

    #[Route('/ordonnance_details/{id<\d+>}', name: 'ordonnance_details')]
    public function ordonnance_details(ManagerRegistry $doctrine, Ordonnance $ordonnance= null, $id): Response
    {
        if(!$ordonnance){
            $this->addFlash('error', "Ce ordonnance n'existe pas !");
            return $this->redirectToRoute("ordonnances");
        }
        return $this->render('patient/details_ordonnance.html.twig', ['ordannance' => $ordonnance]);
    }

    #[Route('/delete_ordonnance/{id?0}', name: 'delete_ordonnance')]
    public function delete_ordonnance(Ordonnance $ordonnance = null, ManagerRegistry $doctrine, Request $request, $id): Response
    {

        $repository = $doctrine->getRepository(Ordonnance::class);
        $ordonnance = $repository->find($id);

        $manager = $doctrine->getManager();
        $manager->remove($ordonnance);

        $manager->flush();

        $message = "Le ordonnance a été supprimer avec succès";


        $this->addFlash("succes", $message);

        return $this->redirectToRoute("ordonnances");

    }
}
