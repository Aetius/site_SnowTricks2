<?php

namespace App\Controller\User;


use App\Form\User\AdminCollectionType;
use App\Repository\UserRepository;
use App\Services\User\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{

    /**
     * @Route ("/admin", name="user_admin", methods={"GET|POST"})
     */
    public function admin(Request $request, AdminService $editor, UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        $form = $this->createForm(AdminCollectionType::class, ['users' => $users]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $editor->update3($form->getData());
            $this->addFlash('success', 'Modifications effectuées');
        }
        return $this->render('admin/AdminTrick.html.twig', [
            'form'=>$form->createView(),
            'users'=>$users,
        ]);
    }


}