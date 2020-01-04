<?php

namespace App\Controller\Trick;


use App\Entity\Trick;

use App\Form\Trick\CreateType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


class TrickController extends AbstractController{
    /**
     * @var TrickRepository
     */
    private $repository;

    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * TrickController constructor.
     * @param Environment $twig
     * @param TrickRepository $repository
     */
    public function __construct(Environment $twig, TrickRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->twig = $twig;
        $this -> em = $em;
    }


    /**
     * @return string
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(TrickRepository $repository, EntityManagerInterface $entityManager)
    {
        /*  $user = new UsersFixtures();
          $user->setEmail('tot<<o@toto.fr');
          $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
          $user->setPassword('toto');*/
        /*
                $entityManager->persist($user);
                $entityManager->flush();*/

        $tricks = $repository->findBy(["publicated"=>"0"],["id"=>'DESC'], "10");
        return $this->render('trick/home.html.twig', [
            'tricks' => $tricks
        ]);
    }
    /**
     *@Route("/trick/new", name="new", methods={"GET|POST"})
     *
     */
    public function new(Request $request)
    {
        $trick = new Trick();
        $form = $this->createForm(CreateType::class, $trick);
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
        ]);
    }

    /**
     *@return string
     *@Route("/trick/{id}", name="trick_show", methods={"GET"})
     */
    public function show(int $id, TrickRepository $repository){
        $trick = $repository->find($id);

        return $this->render('trick/show.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     *@Route("/trick/{id}/edit", name="trick_edit", methods={"GET|POST"})
     */
     public function edit(int $id, TrickRepository $repository, Request $request, Trick $trick, EntityManagerInterface $em){
        $form = $this->createForm(CreateType::class, $trick);

        $form->handleRequest($request);
        $tricks = $repository->find($id);

        if ($form->isSubmitted() && $form->isValid()){
            //$em = $this->getDoctrine()->getManager();  //pareil que EntityManagerInterface
            $em->flush();
            return $this->render('trick/edit.html.twig',[
                'form' => $form->createView(),
                'trick' => $tricks
            ]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $tricks,
            'form' => $form->createView()
        ]);

    }

    /**
     *@Route("/trick/{id}/delete", name="trick_delete", methods={"GET"})
     */
    public function delete(Trick $trick, Request $request)
    {
        if ($this -> isCsrfTokenValid( 'delete' . $trick -> getId(), $request -> get( '_token' ) )) {
            $em = $this -> getDoctrine() -> getManager();
            $em -> remove( $trick );
            $em -> flush();
            return $this->redirectToRoute("home");

        }
    }



}