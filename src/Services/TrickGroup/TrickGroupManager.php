<?php


namespace App\Services\TrickGroup;


use App\Entity\TrickGroup;
use App\Form\Trick\DTO\TrickDTO;

class TrickGroupManager
{


    /**
     * @param TrickDTO $trickDTO
     * @return TrickGroup
     */
    public function manager(TrickDTO $trickDTO)
    {
       if ($trickDTO->trickGroup){
           return $trickDTO->trickGroup;
       }
       $trickGroup = new TrickGroup();
       $trickGroup->setName($trickDTO->trickGroupAdd);

       return $trickGroup;
    }

}