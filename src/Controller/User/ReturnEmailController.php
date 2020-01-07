<?php

namespace App\Controller\User;

use App\Entity\TokenResetPassword;
use App\Entity\User;
use App\Form\User\NewPasswordType;
use App\Repository\UserRepository;
use App\Security\Loggin;
use App\Services\Email\Email;
use App\Services\User\UserEditor;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ReturnEmailController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ReturnEmailController constructor.
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

    public function Login($user)
    {
       $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->container->get('security.token_storage')->setToken($token);
        $this->container->get('session')->set('_security_main', serialize($token));
    }

    /**
     * @Route ("/user/lost_password/{token}", name="lost_password",  methods={"GET|POST"})
     */
    public function lostPassword(TokenResetPassword $tokenResetPassword, User $user,Request $request, Email $emailSevice, UserEditor $userEditor, UserRepository $userRepository, LoggerInterface $log)
    {

      if ($emailSevice->lostPassword($user)){
          $form = $this->createForm(NewPasswordType::class);
          $form->handleRequest($request);

          if (!($form->isSubmitted() && $form->isValid()))
          {
              return $this->render('user/new_password.html.twig', [
                  'form' => $form->createView()
              ]);

          }
          $userEditor->update($user, null, $form->getData());
          $this->Login($user);

      }
      return $this->redirectToRoute('home');
      }








/*


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
    }*/


}