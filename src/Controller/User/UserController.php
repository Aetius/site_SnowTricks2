<?php

namespace App\Controller\User;


use App\Entity\User;
use App\Form\User\EditUserType;
use App\Form\User\LostPasswordType;
use App\Form\User\NewPasswordType;
use App\Form\User\RegistrationUserType;
use App\Notification\EmailNotification;
use App\Repository\EmailRepository;
use App\Repository\UserRepository;
use App\Services\User\UserEditor;
use App\Services\User\UserUpdate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route ("/admin", name="user_admin", methods={"GET|POST"})
     */
    public function admin()
    {
        return $this->render('admin/AdminTrick.html.twig');
    }

    /**
     * @Route ("/inscription", name="user_new", methods={"GET|POST"})
     */
    public function new(Request $request, UserEditor $userCreator)
    {
        $form = $this->createForm(RegistrationUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userCreator->create($form->getData());
            $this->addFlash('success', "flash.registration.success");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
            'errors' => $form->getErrors(true)
        ]);
    }


    /**
     * @Route ("/profile", name="user_update", methods={"GET|POST"})
     */
    public function update(Request $request, UserEditor $userUpdate, EmailRepository $emailUser)
    {
        $email = ($emailUser->findOneBy([
            'user'=>$this->getUser()->getId()
        ]));

        $user = [
            'login' => $this->getUser()->getLogin(),
            'email'=> $email->getEmail()
        ];

        $form = $this->createForm(EditUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser = $userUpdate->update($this->getUser(), $email, $form->getData());
            $this->addFlash('success', "Modifications effectuées");
            $user = array_merge($user, $newUser);
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @Route ("/password_reset", name="user_password_lost", methods={"GET|POST"})
     */
    public function lostPassword(Request $request, EmailNotification $emailNotification, UserRepository $userRepository,
                                 EmailRepository $emailRepository, UserEditor $updateUser)
    {
        $form = $this->createForm(LostPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user = $userRepository->findOneBy(['login' => $form->getData('login')])) {
                $email = $emailRepository->findOneBy(['user'=>$user->getId()])->getEmail();
                $emailFields = [
                   'email' => $email,
                    'id' => $user->getId(),
                   'login'=> $user->getLogin()
                   ];
                $updateUser->update($user);
                $emailNotification->lostPassword($emailFields);
                $this->addFlash('success', "Demande effectuée");
            }
        }
        if ($form->isSubmitted() && $form->isValid() === false) {
            $this->addFlash('danger', "Login invalide");
        }
        return $this->render('user/lost_password.html.twig', [
            'form' => $form->createView()
        ]);
    }



}