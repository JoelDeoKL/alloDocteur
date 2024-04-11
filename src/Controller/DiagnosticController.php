<?php

namespace App\Controller;

use App\Entity\Diagnostic;
use App\Entity\Patient;
use App\Entity\Personnel;
use App\Form\DagnosticType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DiagnosticController extends AbstractController
{
    #[Route('/diagnostics', name: 'diagnostics')]
    public function diagnostics(EntityManagerInterface $entityManager): Response
    {
        $diagnostics = $entityManager->getRepository(Diagnostic::class)->findAll();

        return $this->render('admin/diagnostics.html.twig', ['diagnostics' => $diagnostics]);
    }

    #[Route('/mes_diagnostics', name: 'mes_diagnostics')]
    public function mes_diagnostics(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $patient = $entityManager->getRepository(Patient::class)->findBy(["email" => $session->all()["_security.last_username"]]);

        $diagnostics = $entityManager->getRepository(Diagnostic::class)->findBy(["patient" => $patient[0]]);

        return $this->render('patient/diagnostics.html.twig', ['diagnostics' => $diagnostics]);
    }

    #[Route('/nos_diagnostics', name: 'nos_diagnostics')]
    public function nos_diagnostics(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $personnel = $entityManager->getRepository(Personnel::class)->findBy(["email" => $session->all()["_security.last_username"]]);

        $diagnostics = $entityManager->getRepository(Diagnostic::class)->findBy(["personnel" => $personnel[0]]);

        return $this->render('personnel/diagnostics.html.twig', ['diagnostics' => $diagnostics]);
    }

    #[Route('/editer_diagnostic/{id?0}', name: 'editer_diagnostic')]
    public function editer_diagnostic(Diagnostic $diagnostic = null, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $new = false;
        if(!$diagnostic){
            $new = true;
            $diagnostic = new Diagnostic();
        }

        $form = $this->createForm(DagnosticType::class, $diagnostic);

        //dd($request->request);
        $form->handleRequest($request);

        if($form->isSubmitted()){

            $dateD = new \DateTime();
            $diagnostic->setDateCreation($dateD);

            $manager = $doctrine->getManager();
            $manager->persist($diagnostic);

            $manager->flush();

            if($new){
                $message = "Le diagnostic a été ajouter avec succès";
            }else{
                $message = "Le diagnostic a été editer avec succès";
            }

            $this->addFlash("succes", $message);

            return $this->redirectToRoute("diagnostics");
        }else{
            return $this->render('personnel/add_diagnostic.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/detail_diagnostic/{id<\d+>}', name: 'detail_diagnostic')]
    public function detail_diagnostic(ManagerRegistry $doctrine, Diagnostic $diagnostic= null, $id): Response
    {
        if(!$diagnostic){
            $this->addFlash('error', "Ce diagnostic n'existe pas !");
            return $this->redirectToRoute("diagnostics");
        }
        return $this->render('admin/details_diagnostic.html.twig', ['diagnostic' => $diagnostic]);
    }

    #[Route('/diagnostic_detail/{id<\d+>}', name: 'diagnostic_detail')]
    public function diagnostic_detail(ManagerRegistry $doctrine, Diagnostic $diagnostic= null, $id): Response
    {
        if(!$diagnostic){
            $this->addFlash('error', "Ce diagnostic n'existe pas !");
            return $this->redirectToRoute("diagnostics");
        }
        return $this->render('personnel/details_diagnostic.html.twig', ['diagnostic' => $diagnostic]);
    }

    #[Route('/diagnostic_details/{id<\d+>}', name: 'diagnostic_details')]
    public function diagnostic_details(ManagerRegistry $doctrine, Diagnostic $diagnostic= null, $id): Response
    {
        if(!$diagnostic){
            $this->addFlash('error', "Ce diagnostic n'existe pas !");
            return $this->redirectToRoute("diagnostics");
        }
        return $this->render('patient/details_diagnostic.html.twig', ['diagnostic' => $diagnostic]);
    }

    #[Route('/delete_diagnostic/{id?0}', name: 'delete_diagnostic')]
    public function delete_diagnostic(Diagnostic $diagnostic = null, ManagerRegistry $doctrine, Request $request, $id): Response
    {

        $repository = $doctrine->getRepository(Diagnostic::class);
        $diagnostic = $repository->find($id);

        $manager = $doctrine->getManager();
        $manager->remove($diagnostic);

        $manager->flush();

        $message = "Le diagnostic a été supprimer avec succès";


        $this->addFlash("succes", $message);

        return $this->redirectToRoute("diagnostics");

    }

}
