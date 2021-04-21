<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment", methods={"POST"})
     */
    public function new(Request $request, ArticleRepository $articleRepository,UserRepository $userRepository ,EntityManagerInterface $em): Response
    {
        $article = $articleRepository->findOneBy(["id"=> $request->get("id")]);

        if($article!=null && $request->get("comment")!=""){
            $comment = new Comment();
            $comment->setContent($request->get("comment"));
            $comment->setAuthor($this->getUser());
            $comment->setArticle($article);
            $em->persist($comment);
            $em->flush();
        }
        return $this->redirectToRoute('article_show', ['id'=>$request->get("id") ]);
    }
}
