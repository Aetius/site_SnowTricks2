<?php

namespace App\Controller\User;

use App\Entity\EmailLinkToken;
use App\Entity\User;
use App\Form\User\AdminCollectionType;
use App\Form\User\EditUserType;
use App\Form\User\LostPasswordType;
use App\Form\User\NewPasswordType;
use App\Form\User\RegistrationUserType;
use App\Notification\EmailNotification;
use App\Repository\UserRepository;
use App\Security\TokenEmail;
use App\Services\Email\Mailer;
use App\Services\User\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;


class UserController extends AbstractController
{
    /**
     * @Route ("/user/inscription", name="user_new", methods={"GET|POST"})
     */
    public function new(Request $request, UserManager $userCreator, EmailNotification $notification)
    {
        $form = $this->createForm(RegistrationUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userCreator->create($form->getData());
            $notification->confirmEmail($user);
            $this->addFlash('success', "flash.registration.success");
            $this->Login($user);
            return $this->redirectToRoute('home');
        }
        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
            'errors' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route ("/user/profile", name="user_update", methods={"GET|POST"})
     * @IsGranted("ROLE_USER")
     */
    public function update(Request $request, UserInterface $user, UserManager $userUpdate)
    {
        $form = $this->createForm(EditUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                /** @var User $user */
            $userUpdate->update($user, $form->getData());
            $userUpdate->save($user);
            $this->addFlash('success', "Modifications effectuées");
        }
        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @Route ("/password_lost", name="user_password_lost", methods={"GET|POST"})
     */
    public function lostPassword(Request $request, EmailNotification $emailNotification, UserRepository $userRepository,
                                 TokenEmail $token, UserManager $editor)
    {
        $form = $this->createForm(LostPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user = $userRepository->findOneBy(['login' => $form->getData()->login])) {
                $editor->resetPassword($user);
            }
            $this->addFlash('success', "Demande effectuée");
        }
        return $this->render('user/password_lost.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/confirm_email/{token}", name="user_confirm_email",  methods={"GET"})
     */
    public function confirmEmail(EmailLinkToken $emailLinkToken, User $user, Mailer $emailSevice)
    {

        if ($emailSevice->validationEmail($user) === true) {
            $this->addFlash('success', "L'email a bien été enregistré");
        } else {
            $this->addFlash('danger', "L'adresse email n'a pu être enregistrée. Veuillez Réessayer!");
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route ("/password_reset/{token}", name="user_password_reset",  methods={"GET|POST"})
     */
    public function resetPassword(EmailLinkToken $emailLinkToken, User $user, Request $request,
                                  Mailer $emailSevice, UserManager $userEditor)
    {
        if ($emailSevice->lostPassword($user) === true) {
            $form = $this->createForm(NewPasswordType::class);
            $form->handleRequest($request);

            if (!($form->isSubmitted() && $form->isValid())) {
                return $this->render('user/new_password.html.twig', [
                    'form' => $form->createView()
                ]);
            }
            $userEditor->update($user, $form->getData());
            $this->Login($user);
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route ("/admin", name="user_admin", methods={"GET|POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function administratorUsers(Request $request, UserManager $userManager, UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        $form = $this->createForm(AdminCollectionType::class, ['users' => $users]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $userManager->adminEditUser($form->getData());
            $this->addFlash('success', 'Modifications effectuées');
        }
        return $this->render('admin/AdminTrick.html.twig', [
            'form'=>$form->createView(),
            'users'=>$users,
        ]);
    }



    protected function Login($user)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->container->get('security.token_storage')->setToken($token);
        $this->container->get('session')->set('_security_main', serialize($token));
    }


}