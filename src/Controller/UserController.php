<?php

namespace App\Controller;


use App\Entity\Trick;
use App\Entity\User;
use App\Form\CreateUserType;
use App\Form\User\CreateType;
use App\Form\User\LostPasswordType;
use App\Repository\UserRepository;
use App\Security\PasswordEncode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this -> em = $em;
    }

    /**
     *@Route ("/profile", name="user_admin", methods={"GET|POST"})
     */
    public function admin(){
        return $this->render('admin/AdminTrick.html.twig');
    }

    /**
     *@Route ("/inscription", name="user_new", methods={"GET|POST"})
     */
    public function new (Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(CreateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', "Création du compte effectuée");
            return $this->redirectToRoute('app_login');
        }
        if ($form->isSubmitted() && $form->isValid() == false){
            $this->addFlash('danger', "Echec lors de l'inscription");
        }
        return $this->render('user/new.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     *@Route ("/profile", name="user_update", methods={"GET|POST"})
     */
    public function update(Request $request)
    {

    }


    /**
     * @param User $user
     *@Route ("/password_reset", name="user_password_lost", methods={"GET|POST"})
     */
    public function lostPassword(Request $request, UserRepository $userRepository)
    {
       $user = new User();
        $form = $this->createForm(LostPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            if ($userRepository->findOneBy(['login'=> $user->getLogin()])){
//ajouter le mail.
//envoi email avec token
                $this->addFlash('success', "Demande effectuée");
            }
        }
        if ($form->isSubmitted() && $form->isValid() == false){
            $this->addFlash('danger', "Login invalide");
        }


        return $this->render('user/lost_password.html.twig', [
            'form'=>$form->createView()
        ]);
    }


}