<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Niveau;
use App\Entity\Matiere;
use App\Entity\Chapitre;
use App\Entity\Exercice;
use App\Repository\ClasseRepository;
use App\Repository\NiveauRepository;
use App\Repository\MatiereRepository;
use App\Repository\ChapitreRepository;
use App\Repository\ExerciceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CorroController extends AbstractController
{
    /**
     * @Route("/corro/{classename}", name="corro")
     */
    public function index(string $classename, ClasseRepository $classeRepo)
    {
        $classe = $classeRepo->findOneBy(
            ['nom' => $classename]
        );

        return $this->render('corro/index.html.twig', [
            'classe' => $classe
        ]);
    }

    /**
     * @Route("/", name="accueil")
    */
    public function accueil(/*NiveauRepository $nivRepo*/)
    {
        //$niveaux = $nivRepo->findAll();
        return $this->render('corro/accueil.html.twig');
    }

    /**
     * @Route("/exercice/{id}", name="exercice")
     */
    public function exercice(Matiere $matiere, ChapitreRepository $chapRepo, ExerciceRepository $exoRepo)
    {
        $chapitres = $chapRepo->findBy(
            ['matiere' => $matiere->getId()]
        );

        $exercices = array();

        foreach($chapitres as $chap){
            if($exercices)
                $exercices = array_merge($exercices, $exoRepo->findBy(['chapitre' => $chap->getId()]));
            
            else
                $exercices = $exoRepo->findBy(['chapitre' => $chap->getId()]);
        }

        return $this->render('corro/exercice.html.twig', [
            'exercices' => $exercices,
            'chapitres' => $chapitres,
            'matiere' => $matiere
        ]);
    }

    /**
     * @Route("/menu/{id}", name="menu")
    */
    public function menu(Classe $classe, MatiereRepository $matRepo, ChapitreRepository $chapRepo)
    {
        $matieres = $matRepo->findBy(array('classe' => $classe->getId()));
        $chapitres = $chapRepo->findAll();

        return $this->render('corro/menu.html.twig', [
            'classe' => $classe,
            'matieres' => $matieres,
            'chapitres' => $chapitres
        ]);
    }

    /**
     * @Route("/exo_ac/{id}", name="exo_ac")
     */
    public function exo_ac(Classe $classe)
    {
        return $this->render('corro/exercice.html.twig', [
            'classe' => $classe
        ]);        
    }

    /**
     * @Route("/show/{filename}", name="show_file")
     */
    public function show(string $filename)
    {
        return $this->render('corro/exercice.html.twig', [
            'fichier' => $filename
        ]);
    }
}
