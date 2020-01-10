<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\User\AdminCollectionType;
use App\Form\User\AdminType;
use App\Repository\UserRepository;
use App\Services\User\AdminService;
use App\Services\User\EditorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    /**
     * @Route ("/admin", name="user_admin", methods={"GET|POST"})
     */
    public function admin(Request $request, EditorService $editor, UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        /*dd("pb : mettre en place un système permettant de générer le form plusieurs fois,
        il doit être lié à un seul utilisateur.
        actuellement : un form pour tous les utilisateurs => provoque une erreur, car je veux l' afficher plusieurs fois. ");*/

        $form = $this->createForm(AdminCollectionType::class, ['users' => $users]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $editor->update();
            $this->addFlash('success', 'Modifications effectuées');
        }
        return $this->render('admin/AdminTrick.html.twig', [
            'form'=>$form->createView(),
            'users'=>$users,
        ]);
    }


    /**
     * @Route ("/admin2", name="user_show_admin_2", methods={"GET|POST"})
     */
    public function showAdmin2 (UserRepository $userRepository, Request $request)
    {
        $users = $userRepository->findAll();
        $form = $this->createForm(AdminType::class);
        $choice = [
            'User'=> 'ROLE_USER',
            'Editor'=> 'ROLE_EDITOR',
            'Administrator'=> 'ROLE_ADMIN'
        ];



        return $this->render('admin/AdminTrick2.html.twig', [
            'form'=>$form->createView(),
            'users'=>$users/*,
            'roles'=>$choice*/
        ]);
    }

    /**
     * @Route ("/admin2/{id}", name="user_admin_2", methods={"POST"})
     */
    public function admin2(User $user, Request $request, AdminService $service, UserRepository $userRepository)
    {

        $users = $userRepository->findAll();
        $form = $this->createForm(AdminType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            dd('coucou');
        }
       // dd( $form->submit($request->request->get($form->getName())));



       // $form->submit($request->request->get($form->getName()));

        $submittedToken = $request->request->get('_token');
        $tokenId = 'update_admin'.$user->getId();
       dump($form->getErrors());

       // if ($this->isCsrfTokenValid("$tokenId", $submittedToken)) {

        if ($form->isSubmitted() && $form->isValid()){ dd('ici');
            $service->update();
            $this->addFlash('success', 'Modifications effectuées');
        }
        dd($user);
        return $this->render('admin/AdminTrick2.html.twig', [

            'users'=>$users
        ]);
    }
}