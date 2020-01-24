<?php


namespace App\Controller\Picture;


use App\Entity\Picture;
use App\Form\Picture\EditType;
use App\Repository\PictureRepository;
use App\Services\Picture\EditorService;
use App\Services\Trick\UploadService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{


    /**
     * @Route("/edit/picture/{id}/delete", name="picture_delete", methods={"GET"})
     */
    public function delete(Picture $picture, Request $request, EditorService $editPhoto)
    {
        $trickId = $picture->getTrick()->getId();
        if ($this->isCsrfTokenValid('delete'.$picture->getId(), $request->get('_token'))) {
            $editPhoto->delete($picture);
            $this->addFlash('success', 'deleting_photo');
            return $this->redirectToRoute("trick_edit", ['id' => $trickId]);
        }
    }

    /**
     * @Route("/edit/picture/{id}", name="picture_edit", methods={"GET|POST"})
     */
    public function edit(Picture $picture, Request $request, EditorService $editPhoto)
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
     */
    public function deleteOrphan(string $uploadsPath, PictureRepository $repository)
    {
        $filesDeleted=0;
        $picturesDB = $repository->findAll();
        foreach ($picturesDB as $file)
        {
            $namesDb[] = $file->getFilename();
        }
        $picturesProject = scandir($uploadsPath.'/'.UploadService::ARTICLE_IMAGE);

       foreach ($picturesProject as $picture){
            if (!in_array($picture, $namesDb)  ){
               if (!(is_dir($uploadsPath.'/'.UploadService::ARTICLE_IMAGE.'/'.$picture))){
                   unlink($uploadsPath.'/'.UploadService::ARTICLE_IMAGE.'/'.$picture);
                   $filesDeleted =$filesDeleted+1;
               }
            }
        }

        $picturesProjectThumbnails = scandir($uploadsPath.'/'.UploadService::THUMBNAIL_IMAGE);

        foreach ($picturesProjectThumbnails as $thumbnail){
            if (!in_array($thumbnail, $namesDb)  ){
                if (!(is_dir($uploadsPath.'/'.UploadService::THUMBNAIL_IMAGE.'/'.$thumbnail))){
                    unlink($uploadsPath.'/'.UploadService::THUMBNAIL_IMAGE.'/'.$thumbnail);
                    $filesDeleted =$filesDeleted+1;
                }
            }
        }

        $this->addFlash('success', "$filesDeleted fichier(s) supprimés");
        return $this->redirectToRoute("home");
    }



}