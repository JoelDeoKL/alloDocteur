<?php

namespace App\Controller;

use App\Entity\Examen;
use App\Form\ExamenType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ExamenController extends AbstractController
{
    #[Route('/examens', name: 'examens')]
    public function examens(EntityManagerInterface $entityManager): Response
    {
        $examens = $entityManager->getRepository(Examen::class)->findAll();

        return $this->render('admin/examens.html.twig', ['examens' => $examens]);
    }

    #[Route('/editer_examen/{id?0}', name: 'editer_examen')]
    public function editer_examen(Examen $examen = null, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $new = false;
        if(!$examen){
            $new = true;
            $examen = new Examen();
        }

        $form = $this->createForm(ExamenType::class, $examen);

        //dd($request->request);
        $form->handleRequest($request);

        if($form->isSubmitted()){

            $dateD = new \DateTime();
            $examen->setDateExamen($dateD);

            $manager = $doctrine->getManager();
            $manager->persist($examen);

            $manager->flush();

            if($new){
                $message = "Le examen a été ajouter avec succès";
            }else{
                $message = "Le examen a été editer avec succès";
            }

            $this->addFlash("succes", $message);

            return $this->redirectToRoute("examens");
        }else{
            return $this->render('admin/add_examen.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/detail_examen/{id<\d+>}', name: 'detail_examen')]
    public function detail_examen(ManagerRegistry $doctrine, Examen $examen= null, $id): Response
    {
        if(!$examen){
            $this->addFlash('error', "Ce examen n'existe pas !");
            return $this->redirectToRoute("examens");
        }
        return $this->render('admin/examen_details.html.twig', ['examen' => $examen]);
    }

    #[Route('/delete_examen/{id?0}', name: 'delete_examen')]
    public function delete_examen(Examen $examen = null, ManagerRegistry $doctrine, Request $request, $id): Response
    {

        $repository = $doctrine->getRepository(Examen::class);
        $examen = $repository->find($id);

        $manager = $doctrine->getManager();
        $manager->remove($examen);

        $manager->flush();

        $message = "Le examen a été supprimer avec succès";


        $this->addFlash("succes", $message);

        return $this->redirectToRoute("examens");

    }
}
