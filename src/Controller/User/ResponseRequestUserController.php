<?php

namespace App\Controller\User;

use App\Form\User\NewPasswordType;
use App\Repository\UserRepository;
use App\Services\Email\Email;
use App\Services\User\UserEditor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResponseRequestUserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ResponseRequestUserController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route ("/user/confirm_email", name="confirm_email",  methods={"GET"})
     */
    public function confirmEmail(Email $emailSevice, Request $request)
    {
        if ($emailSevice->validationEmail($request->get('email'), $request->get('login'),
                $request->get('pass')) === true) {
            $this->addFlash('success', "L'email a bien été enregistré");

        } else {
            $this->addFlash('danger', "L'adresse email n'a pu être enregistrée. Veuillez Réessayer!");
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route ("/user/lost_password", name="lost_password",  methods={"GET|POST"})
     */
    public function lostPassword(Request $request, Email $emailSevice, UserEditor $userEditor, UserRepository $userRepository)
    {
        if ($emailSevice->lostPassword(
            $request->get('login'),
            $request->get('email'),
            $request->get('pass')
            ) === true)
        {
            $user = $userRepository->findOneBy(["login"=>$request->get('login')]);
            $pass['password'] = $request->get('pass');
            $form = $this->createForm(NewPasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $userEditor->update($user,null, $form->getData());
                return $this->redirectToRoute('user_update');
            }
            return $this->render('user/new_password.html.twig', [
                'form' => $form->createView()
            ]);


        }
        return $this->redirectToRoute('home');
    }


}