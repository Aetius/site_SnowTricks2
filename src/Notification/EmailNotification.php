<?php

namespace App\Notification;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class EmailNotification extends AbstractController
{


    const FROM = 'hello@yahoo.fr';

    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(Environment $renderer, MailerInterface $mailer)
    {
        $this->renderer = $renderer;
        $this->mailer = $mailer;
    }

    public function confirmEmail(User $user)
    {

        $email = (new TemplatedEmail())
            ->from(self::FROM)
            ->to($user->getEmail())
            ->subject('Confirmation de votre email')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('email/emailConfirm.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'pass' => $user->getEmailLinkToken()->getToken(),
                'login' => $user->getLogin(),
                'emailTo' => $user->getEmail(),
                'url' => "user_confirm_email",
            ]);

        $this->mailer->send($email);
    }


    public function lostPassword(User $user)
    {
        $email = (new TemplatedEmail())
            ->from(self::FROM)
            ->to($user->getEmail())
            ->subject('Confirmation de votre email')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('email/resetPassword.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'pass' => $user->getEmailLinkToken()->getToken(),
                'login' => $user->getLogin(),
                'emailTo' => $user->getEmail(),
                'url' => 'user_password_reset',
            ]);

        $this->mailer->send($email);

    }

}
