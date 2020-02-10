<?php

namespace App\Controller\Trick;


use App\Entity\Trick;
use App\Form\Trick\CreateType;
use App\Form\Trick\DTO\TrickDTO;
use App\Form\Trick\EditType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Services\Trick\TrickManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class TrickController extends AbstractController
{

    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(TrickRepository $repository)
    {
        $tricks = $repository->findBy(["publicated" => "1"], ["id" => 'DESC'], "10");
        return $this->render('trick/home.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("/page/{id}", name="home_tricks", methods={"GET"})
     */
    public function showTricksHomePage(int $id, TrickRepository $repository)
    {
        $min = 10 + ($id * 10);
        $hideButton = false;
        $tricks = $repository->findByMinMax($min);

        if (count($tricks) < 10) {
            $hideButton = true;
        }

        return $this->render('/trick/_home_tricks.html.twig', [
            'tricks' => $tricks,
            'hideButton' => $hideButton
        ]);
    }

    /**
     * @Route("/trick/new", name="trick_new", methods={"GET|POST"})
     * @IsGranted("ROLE_EDITOR")
     */
    public function new(Request $request, TrickManager $service)
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick = $service->create($form->getData());
            $service->save($trick);
            $this->addFlash('success', "Le trick a bien été créé!!");
            return $this->redirectToRoute(('home'));
        }

        return $this->render('trick/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/trick/delete/{id}", name="trick_delete", methods={"GET"})
     * @IsGranted("ROLE_EDITOR")
     */
    public function delete(Trick $trick, Request $request, TrickManager $service)
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->get('_token'))) {
            $service->delete($trick);
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/trick/{id}", name="trick_show", methods={"GET"})
     */
    public function showOneTrick(Trick $trick, CommentRepository $commentRepository)
    {
        $form = $this->createForm(\App\Form\Comment\CreateType::class);
        $comments = $commentRepository->findBy(['trick' => $trick->getId()], ["dateCreation" => "DESC"], 2);

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }


    /**
     * @Route("/edit/trick/{id}", name="trick_edit", methods={"GET|POST"})
     * @IsGranted("ROLE_EDITOR")
     */
    public function edit(Trick $trick, Request $request, TrickManager $service )
    {
        $dto = TrickDTO::createFromTrick($trick);
        $form = $this->createForm(EditType::class,$dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->edit($dto, $trick, $form->get('pictureFiles')->getData());
            $service->save($trick);
            $this->addFlash('success', "Le trick a bien été mis à jour!!");
            return $this->redirectToRoute('trick_edit', ['id' => $trick->getId()]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);

    }



}