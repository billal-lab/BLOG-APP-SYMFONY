<?php


namespace App\Service;

use App\Repository\ArticleRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Request;




class Paginator{

    private $repository;

    private $limit;

    public function __construct(ServiceEntityRepository $repository, int $limit)
    {   
        $this->repository = $repository;
        $this->limit = $limit;
    }

    public  function paginate(Request $request):array
    {
        $page = $this->getPage($request);
        $articles = $this->repository->findBy([],[],$this->limit,($page*$this->limit)-$this->limit);
        return $articles;
    }

    public function getPage(Request $request): int
    {
        $page = $request->query->get("page",1);
        if($page<1) $page=1;
        return $page;
    }

    public  function getLimit(): int
    {
        return $this->limit;
    }


}