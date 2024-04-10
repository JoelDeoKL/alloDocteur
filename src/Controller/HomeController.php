<?php

namespace App\Controller;

use App\Entity\Diagnostic;
use App\Entity\Dossier;
use App\Entity\Examen;
use App\Entity\Fiche;
use App\Entity\Ordonnance;
use App\Entity\Patient;
use App\Entity\Personnel;
use App\Entity\Signalement;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute("app_login");
    }

    #[Route('/admin', name: 'admin')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $examens = $entityManager->getRepository(Examen::class)->findAll();
        $patients = $entityManager->getRepository(Patient::class)->findAll();
        $dossiers = $entityManager->getRepository(Dossier::class)->findAll();
        $diagnostics = $entityManager->getRepository(Diagnostic::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'nbreExamen' => count($examens),
            'nbrePatient' => count($patients),
            'nbreDiag' => count($diagnostics),
            'nbreDossier' => count($dossiers),
        ]);
    }

    #[Route('/patient', name: 'patient')]
    public function patient(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $patient = $entityManager->getRepository(Patient::class)->findBy(["email" => $session->all()["_security.last_username"]]);

        $examens = $entityManager->getRepository(Examen::class)->findBy(["patient" => $patient[0]]);
        $diagnostics = $entityManager->getRepository(Diagnostic::class)->findBy(["patient" => $patient[0]]);
        $ordonnances = $entityManager->getRepository(Ordonnance::class)->findBy(["patient" => $patient[0]]);
        $fiches = $entityManager->getRepository(Fiche::class)->findBy(["patient" => $patient[0]]);

        return $this->render('patient/index.html.twig', [
            'nbreExamen' => count($examens),
            'nbreDiag' => count($diagnostics),
            'nbreFiche' => count($fiches),
            'nbreOrdo' => count($ordonnances),
        ]);
    }

    #[Route('/medecin', name: 'medecin')]
    public function medecin(): Response
    {
        return $this->render('personnel/index.html.twig');
    }

    #[Route('/redirection', name: 'redirection')]
    public function redirection(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();

        $user = $entityManager->getRepository(User::class)->findBy(["email" => $session->all()["_security.last_username"]]);

        if ($user[0]->getRole() == "Patient"){
            return $this->redirectToRoute("patient");
        }elseif ($user[0]->getRole() == "Medecin"){
            return $this->redirectToRoute("medecin");
        }elseif ($user[0]->getRole() == "Admin"){
            return $this->redirectToRoute("admin");
        }else{
            return $this->redirectToRoute("app_logout");
        }
    }

    #[Route('/profil', name: 'profil')]
    public function profil(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $user = $entityManager->getRepository(User::class)->findBy(["email" => $session->all()["_security.last_username"]]);

        if($user[0]->getRole() == "Personnel"){
            $personnel = $entityManager->getRepository(Personnel::class)->findBy(["email" => $session->all()["_security.last_username"]]);
            $diagnostic = count($entityManager->getRepository(Diagnostic::class)->findBy(["personnel" => $personnel[0]]));
            $examen = count($entityManager->getRepository(Examen::class)->findBy(["personnel" => $personnel[0]]));
            $ordonnance = count($entityManager->getRepository(Ordonnance::class)->findBy(["personnel" => $personnel[0]]));
            $signal = count($entityManager->getRepository(Signalement::class)->findBy(["personnel" => $personnel[0]]));

            return $this->render('personnel/profil.html.twig', [
                'personnel' => $personnel,
                'diagnostic' => $diagnostic,
                'examen' => $examen,
                'ordonnance' => $ordonnance,
                'signal' => $signal
            ]);
        }elseif ($user[0]->getRole() == "Patient"){
            $patient = $entityManager->getRepository(Patient::class)->findBy(["email" => $session->all()["_security.last_username"]]);
            $diagnostic = count($entityManager->getRepository(Diagnostic::class)->findBy(["patient" => $patient[0]]));
            $examen = count($entityManager->getRepository(Examen::class)->findBy(["patient" => $patient[0]]));
            $ordonnance = count($entityManager->getRepository(Ordonnance::class)->findBy(["patient" => $patient[0]]));
            $signal = count($entityManager->getRepository(Signalement::class)->findBy(["patient" => $patient[0]]));

            return $this->render('patient/profil.html.twig', [
                'patient' => $patient,
                'diagnostic' => $diagnostic,
                'examen' => $examen,
                'ordonnance' => $ordonnance,
                'signal' => $signal
            ]);
        }

    }

}
