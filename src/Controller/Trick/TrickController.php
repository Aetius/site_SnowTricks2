<?php

namespace App\Controller\Trick;


use App\Entity\Trick;

use App\Form\Trick\CreateType;
use App\Form\Trick\DTO\CreateDTO;
use App\Form\Trick\EditType;
use App\Repository\TrickRepository;
use App\Services\Cache\ImageCache;
use App\Services\Trick\EditorService;
use App\Services\Trick\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\File\LoaderInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
           /* 'trick' => $trick,*/
            'form' => $form->createView()
        ]);
    }



    /**
     *@return string
     *@Route("/trick/{id}", name="trick_show", methods={"GET"})
     */
    public function show(Trick $trick)
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick
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
            $service->edit($form->getData(), $trick,  $form->get('filePicture')->getData());
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
    public function delete(Trick $trick, Request $request)
    {
        if ($this -> isCsrfTokenValid( 'delete' . $trick -> getId(), $request -> get( '_token' ) )) {
            $em = $this -> getDoctrine() -> getManager();
            $em -> remove( $trick );
            $em -> flush();
            return $this->redirectToRoute("home");
        }
    }



    /**
     *@Route("/test", name="test")
     */
    public function test(TrickRepository $repository, ImageCache $cache)
    {

        $tricks = $repository->findOneBy(['id'=>551]);

        $path = ('uploads/'.$tricks->getPicturesPath()[0]);
        $image = $cache->create($path);
        /*     $imagine = new \Imagine\Gd\Imagine();
             $image = $imagine->open($path);
             $size = new Box(200, 200);
             $mode   = ImageInterface::THUMBNAIL_INSET;
             $image
                 ->thumbnail($size, $mode);*/

        /*$test = explode("/", $tricks->getPicturesPath()[0] );
     $image = "uploads/trick_images/thumbnails/". $test[1];*/


        return $this->render('trick/test.html.twig', [
            'tricks' => $tricks,
            'image'=> $image
        ]);
    }




}