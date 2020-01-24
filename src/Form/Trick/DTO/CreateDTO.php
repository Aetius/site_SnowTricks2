<?php


namespace App\Form\Trick\DTO;

use App\Validator\UniqueTrick;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Trick;

class CreateDTO extends EditDTO
{

    /**
     * @Assert\Length(min=5, max=100)
     * @Assert\UniqueEntity
     */
    public $title;


}
