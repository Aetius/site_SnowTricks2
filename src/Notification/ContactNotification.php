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

class ContactNotification extends AbstractController
{
     const FROM = 'dontomberry@outlook.com';
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
            ->from('dontomberry@outlook.com')
            ->to('thomas.brumain@hotmail.fr')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);

    }

    public function confirmEmail($to, $login, $idUser)
    {
        $email = (new TemplatedEmail())
            ->from(self::FROM)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Confirmation de votre email')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('user/emailConfirm.html.twig')
            // pass variables (name => value) to the template
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'pass'=>(password_hash($idUser.'emailConfirm', PASSWORD_ARGON2I, ['cost'=> 12])),
                'login'=>$login,
                'emailTo'=>$to,
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