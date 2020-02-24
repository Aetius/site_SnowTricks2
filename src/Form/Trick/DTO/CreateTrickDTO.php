<?php


namespace App\Form\Trick\DTO;


use App\Validator\VideoFormatOk;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTrickDTO extends TrickDTO
{
    /**
     * @Assert\All({
     *    @Assert\NotBlank(),
     *    @Assert\NotNull()
     *     })
     */
    public $videos;

}