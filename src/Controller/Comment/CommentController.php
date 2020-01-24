<?php


namespace App\Controller\Comment;


use App\Entity\Trick;
use App\Entity\User;
use App\Form\Comment\CreateType;
use App\Form\Comment\DTO\CommentDTO;
use App\Repository\UserRepository;
use App\Services\Comment\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentController extends AbstractController
{
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

}