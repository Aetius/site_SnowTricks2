<?php


namespace App\Form\Trick\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EditDTO
{
    /**
     * @Assert\Length(min=5, max=100)
     */
    public $title;


    /**
     * @Assert\Length(min=10)
     *
     */
    public $description;

    /**
     * @var
     * @Assert\All({
     *     @Assert\Image(maxSize="3M")
     *     })
     */
    public $pictureFiles;

}