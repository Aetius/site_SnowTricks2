<?php


namespace App\Form\Trick\DTO;


use App\Entity\Trick;
use App\Form\Video\DTO\VideoDTO;
use App\Validator\UniqueTrick;
use App\Validator\VideoFormatOk;
use Symfony\Component\Validator\Constraints as Assert;

class TrickDTO
{
    /**
     * @Assert\Length(min=5, max=100)
     * @Assert\NotNull(groups={'create'})
     * @UniqueTrick()
     */
    public $title;


    /**
     * @Assert\Length(min=10)
     * @Assert\NotNull()
     */
    public $description;

    /**
     * @Assert\All({
     *     @Assert\Image(
     *     maxSize="3M")
     *     })
     */
    public $pictureFiles;

    /**
     *
     */
    public $id;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public $trickGroup;


    /**
     * @Assert\All({
     *    @VideoFormatOk(groups={'edit', 'create'}),
     *    @Assert\NotBlank(groups={'create'}),
     *    @Assert\NotNull(groups={'create'})
     *     })
     */
    public $videos;

    public function __construct()
    {
        $this->videos[] = new VideoDTO();
    }


    static function createFromTrick(Trick $trick): TrickDTO
    {
        $dto = new TrickDTO();
        $dto->title = $trick->getTitle();
        $dto->description = $trick->getDescription();
        $dto->trickGroup = $trick->getTrickGroup();
        $dto->id = $trick->getId();
        return $dto;
    }

}