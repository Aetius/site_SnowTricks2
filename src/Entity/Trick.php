<?php

namespace App\Entity;

use App\Services\Picture\UploadService;
use App\Services\Upload\Uploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @UniqueEntity("title")
 */
class Trick
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="string")
     * @Assert\Length(min=5, max=100)
     */
    private $title;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datePublication;

    /**
     * @ORM\Column(type="text")
     *  * @Assert\Length(min=10)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default" = false})
     */
    private $publicated;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateUpdate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="trick", orphanRemoval=true, cascade={"persist"})
     */
    private $pictures;

    /**
     * @var array
     */
    private $picturesPath = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="trick", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup", inversedBy="trick", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $trickGroup;



    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->publicated = true;
        $this->pictures = new ArrayCollection();
        $this->datePublication = new \DateTime();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): self
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublicated(): ?bool
    {
        return $this->publicated;
    }

    public function setPublicated(bool $publicated): self
    {
        $this->publicated = $publicated;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(?\DateTimeInterface $dateUpdate): self
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setTrick($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getTrick() === $this) {
                $picture->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getPicturesPath(): array
    {

        foreach ($this->getPictures() as $picture)
        {
            $this->picturesPath[] = Uploader::ARTICLE_IMAGE.'/'.$picture->getFilename();
        }
        return $this->picturesPath;
    }

    /**
     * @param array $picturesPath
     */
    public function setPicturesPath(array $picturesPath): void
    {
        $this->picturesPath = $picturesPath;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    /**
    * @return TrickGroup
     */
    public function getTrickGroup()
    {
        return $this->trickGroup;
    }

    public function setTrickGroup(TrickGroup $trickGroup): self
    {
        $this->trickGroup = $trickGroup;

        return $this;
    }

 /*   public function addTrickGroup(TrickGroup $trickGroup)
    {
        if (!$this->trickGroup->contains($trickGroup)){
            $this->trickGroup[]= $trickGroup;
            $trickGroup->addTrick($this);
        }
        return $this;
    }*/
/*
    public function removeTrickGroup(TrickGroup $trickGroup)
    {
        if ($this->trickGroup->contains($trickGroup)) {
            $this->trickGroup->removeElement($trickGroup);
            // set the owning side to null (unless already changed)
            if ($trickGroup->getName() === $this) {
                $trickGroup->setName(null);
            }
        }

        return $this;
    }*/

}
