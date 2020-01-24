<?php

namespace App\Notification;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
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

    /**
     * @Route("/email")
     */
    public function sendEmail()
    {

        $email = (new Email())

            ->from()
            ->to()

            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);

    }

    public function confirmEmail($user)
    {

        $email = (new TemplatedEmail())
            ->from(self::FROM)
            ->to($user->getEmail())
            ->subject('Confirmation de votre email')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('email/emailConfirm.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'pass'=>$user->getEmailLinkToken()->getToken(),
                'login'=>$user->getLogin(),
                'emailTo'=>$user->getEmail(),
                'url'=>'user/confirm_email',
            ]);

        $this->mailer->send($email);
    }

    /**
     * * @param string $user
     * @param string $email
     * @param string $token
     */
    public function lostPassword($user)
    {
        $email = (new TemplatedEmail())
            ->from(self::FROM)
            ->to($user->getEmail())
            ->subject('Confirmation de votre email')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('email/resetPassword.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'pass'=>$user->getEmailLinkToken()->getToken(),
                'login'=>$user->getLogin(),
                'emailTo'=>$user->getEmail(),
                'url'=>'user/password_reset',
            ]);

        $this->mailer->send($email);

    }


    public function notify(User $contact)  //représentation de la personne que l'on souhaite contacter.
    {
        $message = (new Email())
            ->setFrom('hello')
            ->setTo('contact@agence.fr')
            ->setReplyTo($contact->getEmail())
            ->setBody($this->renderer->render('emails/contact.html.twig', [
                'contact' => $contact
            ]), 'text/html');
        $this->mailer->send($message);
    }
}
