<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class MailerController extends AbstractController
{
    public function sendEmail(MailerInterface $mailer , $email): Response
    {
        $email = (new Email())
            ->from('coworking.spaceproject@gmail.com')
            ->to($email)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Password Alert')
            ->text('Sending emails is fun again!')
            ->html('<p>Your password has been updated</p>');

        $mailer->send($email);
        return $this->redirectToRoute("home");

        // ...
    }
}