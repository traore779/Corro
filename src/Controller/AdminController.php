<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Form\ExerciceType;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/ajouter", name="ajout")
     * @Route("/admin/{id}/modifier", name="modif")
     */
    public function formExercice(Exercice $exercice = null, Request $request)
    {
        if(!$exercice)
        {
            $exercice = new Exercice();
        }

        $formExercice = $this->createForm(ExerciceType::class, $exercice);

        $formExercice->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        //dump($exercice);
        if($formExercice->isSubmitted() && $formExercice->isValid())
        {
            $entityManager->persist($exercice);
            $entityManager->flush();
        }

        return $this->render('admin/formExercice.html.twig', [
            'formExercice' => $formExercice->createView(),
            'modeModif' => $exercice->getId() !== null
        ]);
    }
}
