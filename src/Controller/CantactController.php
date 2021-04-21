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
                $success = EmailSender::send($this->getUser()->getEmail(),
                    "test@test.fr",
                    $cantact->getSubject(),
                    $cantact->getContent(),
                    $mailerInterface);
                if($success){
                    $this->addFlash("success", "votre message a été envoyé");
                    return $this->redirectToRoute('article_index');
                }else {
                    $this->addFlash("danger", "somthing wrong try again");
                }
        }
        return $this->render('cantact/index.html.twig', [
            'cantactForm' => $form->createView(),
        ]);
    }
}
