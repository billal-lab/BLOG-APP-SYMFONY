<?php


namespace App\Service;

use App\Repository\ArticleRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Request;



/**
 * le service gérant la pagination et le filtrage
 * @method Article[]  paginate()
 * @method int        getPage()
 * @method int        getLimit()
 */
class Paginator{


    /**
     * @var ServiceEntityRepository le repository
     */
    private $repository;



    /**
     * @var int la limit des artcile qu'on veut avoir
     */
    private $limit;


    public function __construct(ServiceEntityRepository $repository, int $limit)
    {   
        $this->repository = $repository;
        $this->limit = $limit;
    }



    /**
     * @return array la list des article correspondant a la pagination
     */
    public  function paginate(Request $request, $mots=null, $category=null, $orderBy="DESC"):array
    {
        $page = $this->getPage($request);
        $articles = $this->repository->search($mots, $category, $this->limit, ($page*$this->limit)-$this->limit, $orderBy );
        return $articles;
    }


    /**
     * @param Request
     * @return int le numéro de la page
     */
    public function getPage(Request $request): int
    {
        $page = $request->query->get("page",1);
        if($page<1) $page=1;
        return $page;
    }


    /**
     * @return int la limit d'article qu'on veut a voir
     */
    public  function getLimit(): int
    {
        return $this->limit;
    }

    
}