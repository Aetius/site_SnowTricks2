<?php


namespace App\Form\Trick\DTO;

use App\Validator\UniqueTrick;
use Symfony\Component\Validator\Constraints as Assert;

class TrickDTO
{
    /**
     * @Assert\Length(min=5, max=100)
     * @UniqueTrick()
     */
    public $title;


    /**
     * @Assert\Length(min=10)
     * @UniqueTrick()
     */
    public $description;

    /**
     * @Assert\All({
     *     @Assert\Image(maxSize="3M")
     *     })
     */
    public $pictureFiles;

    /**
     *
     */
    public $id;

}