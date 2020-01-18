<?php


namespace App\Form\Trick\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateDTO
{

    /**
     * @Assert\Length(min=5, max=100)
     *
     */
    public $title;

    public $description;


    public $pictureFiles;

}