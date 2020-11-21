<?php

namespace App\Controller;

use App\Entity\Chapitre;
use App\Entity\Exercice;
use App\Entity\Matiere;
use App\Form\ExerciceType;
use App\Form\MatiereType;
use App\Repository\ExerciceRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @IsGranted("ROLE_ADMIN")
 */
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
     * @Route("/admin/menu", name="admin_menu")
     */
    public function menu()
    {
        return $this->render('admin/adminMenu1.html.twig');
    }

    /**
     * @Route("/admin/ajouter", name="admin_ajout")
     * @Route("/admin/modifier/{id}", name="admin_modif")
     */
    public function formExercice(Exercice $exercice = null, Request $request, SluggerInterface $slugger)
    {
        if(!$exercice)
        {
            $exercice = new Exercice();
        }

        $formExercice = $this->createForm(ExerciceType::class, $exercice);

        $formExercice->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        
        //dump($exercice);
        if($formExercice->isSubmitted() && $formExercice->isValid()){
            $fichier = $formExercice->get('file')->getData();

            if($fichier){
                $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$fichier->guessExtension();

                try {
                    $fichier->move($this->getParameter('exercises_dir'), $newFilename);
                    //dump($this.getParameter('exercises_dir'));
                } catch (FileException $e) {
                    //problème au niveau du chargement de fichier
                }

                $exercice->setFichier($newFilename);
            }

            $entityManager->persist($exercice);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('admin_ajout'));
        }

        return $this->render('admin/formExercice.html.twig', [
            'formExercice' => $formExercice->createView(),
            'modeModif' => $exercice->getId() !== null
        ]);
    }

    /**
     * @Route("/admin/liste", name="admin_liste")
     */
    public function liste(ExerciceRepository $exerciceRepos)
    {
        $exercices = $exerciceRepos->findAll();

        return $this->render('admin/adminListe.html.twig', [
            'exercices' => $exercices
        ]);
    }

    /**
     * @Route("/admin/new_matiere", name="new_matiere")
     */
    public function newMatiere(Matiere $matiere = null, Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        if($matiere == null)
            $matiere = new Matiere();

        $formMatiere = $this->createForm(MatiereType::class, $matiere);
        $formMatiere->handleRequest($request);

        if($formMatiere->isSubmitted() && $formMatiere->isValid()){
            $chapitres = $formMatiere->get('chapitres');
            foreach($chapitres as $chap){
                $exercices = $chap->get('exercices')->getData();
                $chapitre = new Chapitre();

                foreach($exercices as $exo){
                    $originalFilename = pathinfo($exo->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$exo->guessExtension();
    
                    try {
                        $exo->move($this->getParameter('exercises_dir'), $newFilename);
                        //dump($this.getParameter('exercises_dir'));
                    } catch (FileException $e) {
                        //problème au niveau du chargement de fichier
                    }
    
                    $exercice = new Exercice();
                    $exercice->setFichier($newFilename);
                    $chapitre->addExercice($exercice);
                }

                $chapitre->setNom($chap->get('nom')->getData());
                $matiere->addChapitre($chapitre);
            }

            $em->persist($matiere);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_liste'));
        }

        return $this->render('admin/formMatiere.html.twig', [
            'formMatiere' => $formMatiere->createView()
        ]);
    }
}
