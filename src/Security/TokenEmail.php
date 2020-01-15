<?php

namespace App\Security;

use App\Entity\EmailLinkToken;
use App\Entity\User;


class TokenEmail
{


    public function create(User $user, int $action)
    {

        if ($user->getEmailLinkToken() === null){
            $emailToken = new EmailLinkToken();
            $user->setEmailLinkToken($emailToken);
        }
        $token = md5(random_bytes(10));
        $user->getEmailLinkToken()->setToken($token);
        $user->getEmailLinkToken()->setAction($action);
        $user->getEmailLinkToken()->setDateCreation(new \DateTime());

    }
}