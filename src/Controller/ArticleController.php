<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\Paginator;
use App\Service\FileUploader;
use App\Form\SearchArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{

    private $paginator;

    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->paginator = new Paginator($articleRepository, 2);
    }
    
    /**
     * @Route("/", name="article_index", methods={"GET", "POST"})
    */

    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchArticleType::class);
        $form->handleRequest($request);
        
        $mots = null;
        $categorie = null;
        $order = "DESC";

        if($form->isSubmitted() && $form->isValid()){
            $mots =  $form->getData()["mots"];
            $categorie =  $form->getData()["categorie"];
            if($form->getData()["order"]){
                $order= "ASC";
            }
        }
        
        $articles = $this->paginator->paginate($request, $mots,$categorie, $order);

        return $this->render('article/index.html.twig', [
            'searchForm'=> $form->createView(),
            'articles' => $articles,
            'numberOfArticles'=> $this->articleRepository->findNumberOfArticles($mots, $categorie)[0][1],
            'limit'=> $this->paginator->getLimit(),
            'page' => $this->paginator->getPage($request),
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */

    public function new(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $fileUploader = new  FileUploader($this->getParameter("images_directory"),  $slugger);

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get("image")->getData();
            if($image!=null){
                $imageName = $fileUploader->upload($image);
                $article->setImageName($imageName);
                $entityManager->persist($article);
                $entityManager->flush();
                $this->addFlash("success", "Success !");
                return $this->redirectToRoute('article_index');
            }else{
                $this->addFlash("danger", "please select an image");
            }
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article,ArticleRepository $articleRepository ): Response
    {
        $article_neighbours = $article->getCategorie()->getArticles();

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'neighbours' => $article_neighbours,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Article $article, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $fileUploader = new  FileUploader($this->getParameter("images_directory"),  $slugger);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get("image")->getData();

            if($image!=null){
                $imageName = $fileUploader->upload($image);
                $article->setImageName($imageName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
