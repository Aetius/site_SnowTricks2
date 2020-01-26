<?php


namespace App\Controller\Comment;


use App\Entity\Trick;
use App\Entity\User;
use App\Form\Comment\CreateType;
use App\Form\Comment\DTO\CommentDTO;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Services\Comment\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentController extends AbstractController
{
    const NUMBER_OF_COMMENT_BY_VIEW = 2;
    /**
     *@Route("/trick/{id}/comment/new", name="comment_create", methods={"POST"})
     */
    public function create(Trick $trick, Request $request, CommentService $service, UserInterface $userInterface,
                           UserRepository $userRepository)
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);
        $user = $userRepository->findOneBy(['id'=>$userInterface->getId()]);

        if ($form->isSubmitted() && $form->isValid()){
            $service->create($form->getData(), $trick, $user);
            $this->addFlash('success', "Le commentaire a bien été créé!!");
        }
        return $this->redirectToRoute('trick_show', ['id'=>$trick->getId()]);
    }

    /**
     *@Route("/trick/{id}/{pageComment}", name="comment_show_page", methods={"GET|POST"})
     */
    public function show(Trick $trick, string $pageComment, CommentRepository $repository)
    {


        $min = self::NUMBER_OF_COMMENT_BY_VIEW + ($pageComment*self::NUMBER_OF_COMMENT_BY_VIEW);
        $hideButton = false;

        $comments = $repository->findByMinMax($min, $trick->getId()) ;
        if(count($comments)< self::NUMBER_OF_COMMENT_BY_VIEW){
            $hideButton = true;
        }

        return $this->render('/template/_show_comments.html.twig', [
            'comments' => $comments,
            'hideButton'=> $hideButton
        ]);
    }
}