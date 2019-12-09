<?php

namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UserController extends AbstractController{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this -> em = $em;
    }

    /**
     *@Route("/login", name="login", methods={"GET|POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/1login.html.twig',[
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     *@Route("/inscription", name="inscription", methods={"GET|POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //$encoder->encodePassword($user,)
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', "Création du compte effectuée !");
            return $this->redirectToRoute('login');
        }
        if ($form->isSubmitted() && $form->isValid() == false){
            $this->addFlash('danger', "Echec lors de l'inscription");
        }
        return $this->render('user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


/*
$trick = new Trick();
$form = $this->createForm(TrickPublicType::class, $trick);
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()){
$this->em->persist($trick);
$this->em->flush();
$this->addFlash('success', "Le trick a bien été créé!!");
return $this->redirectToRoute(('home'));

}
if ($form->isSubmitted() && $form->isValid()==false) {
    $this -> addFlash( 'danger', "Echec lors de l'enregistrement" );
}
return $this->render('trick/new.html.twig', [
    'trick' => $trick,
    'form' => $form->createView()
]);*/
}