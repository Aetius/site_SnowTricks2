<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\User\EditUserType;
use App\Form\User\LostPasswordType;
use App\Form\User\RegistrationUserType;
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
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

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

       /* $form = $this->createForm(ModifyType::class, $user);*/
        $form = $this->createForm(EditUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser = $userUpdate->update($form->getData(), $this->getUser(), $email);
            //dd($user);
            $this->addFlash('success', "Modifications effectuées");
            $user = array_merge($user, $newUser);
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @param User $user
     * @Route ("/password_reset", name="user_password_lost", methods={"GET|POST"})
     */
    public function lostPassword(Request $request, UserRepository $userRepository)
    {
        $userEntity = new User();


        $form = $this->createForm(LostPasswordType::class, $userEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($user = $userRepository->findOneBy(['login' => $userEntity->getLogin()])) {

//envoi email avec token
                $this->addFlash('success', "Demande effectuée");
            }
        }
        if ($form->isSubmitted() && $form->isValid() == false) {
            $this->addFlash('danger', "Login invalide");
        }


        return $this->render('user/lost_password.html.twig', [
            'form' => $form->createView()
        ]);
    }


}