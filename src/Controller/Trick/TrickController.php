<?php

namespace App\Controller\Trick;


use App\Entity\Trick;

use App\Form\Trick\CreateType;
use App\Form\Trick\EditType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Services\Trick\EditorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


class TrickController extends AbstractController{

    /**
     * @var Environment
     */
    private $twig;


    /**
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    /**
     * @Route("/test/test", name="testtest", methods={"GET"})
     */
    public function testest(){
        return $this->render('test.html.twig');
    }

    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(TrickRepository $repository )
    {
        $tricks = $repository->findBy(["publicated"=>"1"],["id"=>'DESC'], "10");
        return $this->render('trick/home.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("/{id}", name="home_tricks", methods={"GET"})
     */
    public function showTricks(int $id,  TrickRepository $repository)
    {
        $min = 10 + ($id*10);
        $hideButton = false;

        $tricks = $repository->findByMinMax($min) ;
        if(count($tricks)< 10){
            $hideButton = true;
        }

        return $this->render('/template/_home_tricks.html.twig', [
            'tricks' => $tricks,
            'hideButton'=> $hideButton
        ]);
    }


    /**
     *@Route("/edit/trick/new", name="new", methods={"GET|POST"})
     */
    public function new(Request $request, EditorService $service)
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $service->create($form->getData());
            $this->addFlash('success', "Le trick a bien été créé!!");
            return $this->redirectToRoute(('home'));
        }

        return $this->render('trick/new.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     *@Route("/trick/{id}", name="trick_show", methods={"GET"})
     */
    public function show(Trick $trick, CommentRepository $commentRepository)
    {
        $form = $this->createForm(\App\Form\Comment\CreateType::class);
        $comments = $commentRepository->findBy(['trick'=>$trick->getId()], ["dateCreation"=>"DESC"], 2);

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form'=>$form->createView(),
            'comments'=> $comments
        ]);
    }

    /**
     *@Route("/edit/trick/{id}", name="trick_edit", methods={"GET|POST"})
     */
     public function edit(Trick $trick, Request $request, EditorService $service)
     {
        $form = $this->createForm(EditType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $service->edit($form->getData(), $trick,  $form->get('pictureFiles')->getData());
            $this->addFlash('success', "Le trick a bien été mis à jour!!");
          return $this->redirectToRoute('trick_edit', ['id'=> $trick->getId()]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);

    }

    /**
     *@Route("/edit/trick/{id}/delete", name="trick_delete", methods={"GET"})
     */
    public function delete(Trick $trick, Request $request, EditorService $service)
    {
        if ($this -> isCsrfTokenValid( 'delete' . $trick -> getId(), $request -> get( '_token' ) )) {
            $service->delete($trick);
            return $this->redirectToRoute("home");
        }
    }

}