<?php


namespace App\Controller\Picture;


use App\Entity\Picture;
use App\Form\Picture\EditType;
use App\Repository\PictureRepository;
use App\Services\Picture\PictureManager;
use App\Services\Upload\Uploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class PictureController extends AbstractController
{


    /**
     * @Route("/image/{id}/delete", name="picture_delete", methods={"GET"})
     * @IsGranted("ROLE_EDITOR")
     */
    public function delete(Picture $picture, Request $request, PictureManager $editPhoto)
    {
        $trickId = $picture->getTrick()->getId();
        if ($this->isCsrfTokenValid('delete'.$picture->getId(), $request->get('_token'))) {
            $editPhoto->delete($picture);
            $this->addFlash('success', 'deleting_photo');
        }
        return $this->redirectToRoute("trick_edit", ['id' => $trickId]);
    }


    //using Ajax. problem with @is_granted. create of a token if user have the credentials.
    /**
     * @Route("/image/{id}", name="picture_edit", methods={"GET|POST"})
     * @IsGranted("ROLE_EDITOR")
     */
    public function edit(Picture $picture, Request $request, PictureManager $editPhoto, TokenStorageInterface $storage, Session $session)
    {

        $form = $this->createForm(EditType::class, $picture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $editPhoto->edit($form->getData(), $form->get('filePicture')->getData());
            $this->addFlash('success', "Le trick a bien été mis à jour!!");
            return $this->redirectToRoute('trick_edit', ['id'=> $picture->getTrick()->getId()]);
        }

        return $this->render('picture/_edit.html.twig', [
            'picture' => $picture,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/delete_orphan", name="picture_delete_orphan", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteOrphan(string $uploadsPath, PictureRepository $repository)
    {
        $filesDeleted=0;
        $picturesDB = $repository->findAll();
        foreach ($picturesDB as $file)
        {
            $namesDb[] = $file->getFilename();
        }
        $picturesProject = scandir($uploadsPath.'/'.Uploader::ARTICLE_IMAGE);

       foreach ($picturesProject as $picture){
            if (!in_array($picture, $namesDb)  ){
               if (!(is_dir($uploadsPath.'/'.Uploader::ARTICLE_IMAGE.'/'.$picture))){
                   unlink($uploadsPath.'/'.Uploader::ARTICLE_IMAGE.'/'.$picture);
                   $filesDeleted =$filesDeleted+1;
               }
            }
        }

        $picturesProjectThumbnails = scandir($uploadsPath.'/'.Uploader::THUMBNAIL_IMAGE);

        foreach ($picturesProjectThumbnails as $thumbnail){
            if (!in_array($thumbnail, $namesDb)  ){
                if (!(is_dir($uploadsPath.'/'.Uploader::THUMBNAIL_IMAGE.'/'.$thumbnail))){
                    unlink($uploadsPath.'/'.Uploader::THUMBNAIL_IMAGE.'/'.$thumbnail);
                    $filesDeleted =$filesDeleted+1;
                }
            }
        }

        $this->addFlash('success', "$filesDeleted fichier(s) supprimés");
        return $this->redirectToRoute("home");
    }



}