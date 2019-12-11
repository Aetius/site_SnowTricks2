<?php

namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     *@Route ("inscription", name="inscription", methods={"GET|POST"})
     */
    public function new (Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', "Création du compte effectuée");
            return $this->redirectToRoute('login');
        }
        if ($form->isSubmitted() && $form->isValid() == false){
            $this->addFlash('danger', "Echec lors de l'inscription");
        }
        return $this->render('user/new.html.twig', [
            'form'=>$form->createView()
        ]);
    }

}