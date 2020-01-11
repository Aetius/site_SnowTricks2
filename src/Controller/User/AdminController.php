<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\User\AdminCollectionType;
use App\Form\User\AdminType;
use App\Form\User\Admin2Type;
use App\Repository\UserRepository;
use App\Services\User\AdminService;
use App\Services\User\EditorService;
use Doctrine\Common\Collections\ArrayCollection;
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
        $userFormsView = [];

        foreach ( $users as $user){
            $form=$this->createForm(AdminType::class, $user);
            $allForms[$user->getLogin()]=$form;
            $formView = $form->createView();
           $userFormsView[$user->getLogin()]=$formView;
        }

        if (array_key_exists($request->get('login'), $allForms)){
            $userForm = ($allForms[$request->get('login')]);
            $userForm->handleRequest($request);
            if ($userForm->isSubmitted() && $userForm->isValid()){
                $editor->update($userForm->getData());
                $this->addFlash('success', 'Modifications effectuées');
            }
        }

        return $this->render('admin/AdminTrick.html.twig', [
            'forms'=>$userFormsView,
            'users'=>$users
        ]);

    }

    /**
     * @Route ("/admin3", name="user_admin3", methods={"GET|POST"})
     */
    public function admin3(Request $request, AdminService $editor, UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        $form = $this->createForm(AdminCollectionType::class, ['users' => $users]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $editor->update3($form->getData());
            $this->addFlash('success', 'Modifications effectuées');
        }
        return $this->render('admin/AdminTrick3.html.twig', [
            'form'=>$form->createView(),
            'users'=>$users, //déjà intégré dans le form
        ]);
    }


    /**
     * @Route ("/admin2", name="user_show_admin_2", methods={"GET"})
     */
    public function showAdmin2 (UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        $choice = [
            'User'=> 'ROLE_USER',
            'Editor'=> 'ROLE_EDITOR',
            'Administrator'=> 'ROLE_ADMIN'
        ];
        return $this->render('admin/AdminTrick2.html.twig', [
            'users'=>$users,
            'roles'=>$choice
        ]);
    }

    /**
     * @Route ("/admin2/{id}", name="user_admin_2", methods={"POST"})
     */
    public function admin2(User $user, Request $request, AdminService $service)
    {
        $form = $this->createForm(Admin2Type::class/*, $user*/);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $service->update2($user, $form->getData());
            $this->addFlash('success', 'Modifications effectuées');
        }else{
            $this->addFlash('danger', "Les modifications demandées n'ont pu être effectuées");
        }
        return $this->redirectToRoute('user_show_admin_2');
    }


}