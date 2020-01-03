<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User\LostPasswordType;
use App\Repository\EmailRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

class RequestUserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * RequestUserController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     *@Route ("/user/confirm_email/{email}/{login}/{pass}", name="confirm_email",  methods={"GET"})
     */
    public function confirmEmail( $email, $login, $pass,UserRepository $userRepository, EmailRepository $emailRepository)
    {
        $user = $userRepository->findOneBy(['login'=>$login]);
        $emailUser = $emailRepository->findOneBy(['email'=>$email]);
        $userId =$user->getId().'emailConfirm';
        $dateCreationEmail = $emailUser->getDateCreation();
        $limitDateConfirmation = new \DateTime('-7 days');

        if ($emailUser->getUser()->getId() === $user->getId()){
            if ((password_verify($userId, $pass)) && ($dateCreationEmail>$limitDateConfirmation))
            {
                $emailUser->setIsVerified(true);
                $this->entityManager->persist($emailUser);
                $this->entityManager->flush();
            }
        }
        return $this->redirectToRoute('home');

    }



    /**
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