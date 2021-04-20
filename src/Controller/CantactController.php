<?php

namespace App\Controller;

use App\Entity\Cantact;
use App\Form\CantactType;
use App\Service\EmailSender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CantactController extends AbstractController
{
    /**
     * @Route("/cantact", name="cantact", methods={"POST", "GET"})
     */
    public function index(Request $request, MailerInterface $mailerInterface): Response
    {
        $cantact = new Cantact();
        $form = $this->createForm(CantactType::class, $cantact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
                EmailSender::send("test@test.com",
                "test@test.fr",
                $cantact->getSubject(),
                $cantact->getContent(),
                $mailerInterface
            );
        }
        return $this->render('cantact/index.html.twig', [
            'cantactForm' => $form->createView(),
        ]);
    }
}
