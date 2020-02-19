<?php


namespace App\Services\Comment;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\Comment\DTO\CommentDTO;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(CommentDTO $commentDTO, Trick $trick, User $user)
    {
        $comment = new Comment();
        $comment
            ->setTrick($trick)
            ->setUser($user)
            ->setContent($commentDTO->content);
        return $comment;
    }

    public function save(Comment $comment)
    {
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }
}