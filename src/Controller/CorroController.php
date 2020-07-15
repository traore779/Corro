<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Exercice;
use App\Entity\Niveau;
use App\Repository\ExerciceRepository;
use App\Repository\NiveauRepository;

class CorroController extends AbstractController
{
    /**
     * @Route("/corro", name="corro")
     */
    public function index()
    {
        return $this->render('corro/index.html.twig', [
            'controller_name' => 'CorroController',
        ]);
    }

    /**
     * @Route("/", name="accueil")
    */
    public function accueil(NiveauRepository $nivRepo)
    {
        $niveaux = $nivRepo->findAll();
        return $this->render('corro/accueil.html.twig', [
            'niveaux' => $niveaux
        ]);
    }

    /**
     * @Route("/exercice/{id}", name="exercice")
     */
    public function exercice(Niveau $niveau, ExerciceRepository $exoRepo)
    {
        $exercices = $exoRepo->findAll();

        return $this->render('corro/exercice.html.twig', [
            'exercices' => $exercices,
            'niv' => $niveau
        ]);
    }

    /**
     * @Route("/solution/{id}", name="solution")
     */
    public function solution(Exercice $exercice)
    {
        return $this->render('corro/solution.html.twig', [
            'exercice' => $exercice
        ]);
    }
}
