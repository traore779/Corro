<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Message;
use App\Form\DiscussionType;
use App\Form\MessageType;
use App\Repository\DiscussionRepository;
use DateTime;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function index(Request $request, DiscussionRepository $discussionRepos, PaginatorInterface $paginator)
    {
        $donnees = $discussionRepos->findAll();
        $discussions = $paginator->paginate($donnees, $request->query->getInt('page', 1), 6);
        
        return $this->render('forum/index.html.twig', [
            'discussions' => $discussions
        ]);
    }

    /**
     * @Route("/forum/new", name="new_discussion")
     */
    public function discussion(Request $request, UserInterface $user)
    {
        $discussion = new Discussion();

        $formDiscussion = $this->createForm(DiscussionType::class, $discussion);
        $formDiscussion->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if($formDiscussion->isSubmitted() && $formDiscussion->isValid()){

            if ($discussion->getImage() !== null) {
                $file = $formDiscussion->get('image')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move($this->getParameter('images_dir_post'), $fileName);
                } catch (FileException $e) {
                    //return new Response($e->getMessage());
                }

                $discussion->setImage($fileName);
            }

            $discussion->setCreateur($user)
                ->setDateCreation(new \DateTime());

            $entityManager->persist($discussion);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('responses', ['id' => $discussion->getId()]));
        }
        return $this->render('forum/create.html.twig', [
            'formDiscussion' => $formDiscussion->createView()
        ]);
    }

    /**
     * @route("/forum/responses/{id}", name="responses")
     */
    public function response(Discussion $discussion, Request $request, UserInterface $user, SluggerInterface $slugger)
    {
        //$discussion = $discussionRepos->find($id);
        $message = new Message();

        $formMessage = $this->createForm(MessageType::class, $message);
        $formMessage->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if($formMessage->isSubmitted() && $formMessage->isValid()){
            $image = $formMessage->get('image')->getData();

            /*if ($message->getImage() !== null) {
                $file = $formMessage->get('image')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move($this->getParameter('images_dir'), $fileName);
                } catch (FileException $e) {
                    //return new Response($e->getMessage());
                }

                $message->setImage($fileName);
            }*/

            if($image){
                $originalImageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeImageName = $slugger->slug($originalImageName);
                $newImageName = $safeImageName.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move($this->getParameter('images_dir'), $newImageName);
                    //dump($this.getParameter('exercises_dir'));
                } catch (FileException $e) {
                    //problème au niveau du chargement de fichier
                    //return new Response($e->getMessage());
                }

                $message->setImage($newImageName);
            }

            $message->setDate(new \DateTime())
                ->setDiscussion($discussion)
                ->setSender($user);

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('responses', ['id' => $discussion->getId()]));
        }

        return $this->render('forum/response.html.twig', [
            'discussion' => $discussion,
            'formMessage' => $formMessage->createView()
        ]);
    }
}
