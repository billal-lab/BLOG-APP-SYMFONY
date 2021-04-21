<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Article[]    search(string $mots, string $categorie = null, $limit = null, $offset = null, order = null)
 * @method int          findNumberOfArticles(string $mots, string $categorie = null)
 */

class ArticleRepository extends ServiceEntityRepository
{



    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Recherche le nombre d'articles en fonction du formulaire
     * @return int
    */
    public function findNumberOfArticles($mots=null, $categorie=null)
    {
        $query = $this->createQueryBuilder('a')
                    ->select('COUNT(a)');
                    if($mots != null){
                        $query->where('MATCH_AGAINST(a.title, a.description) AGAINST (:mots boolean)>0')
                            ->setParameter('mots', $mots);
                    }
                    if($categorie != null){
                        $query->leftJoin('a.categorie', 'c');
                        $query->andWhere('c.id = :id')
                            ->setParameter('id', $categorie->getId());
                    }
        return $query->getQuery()->getResult();
    }



    
    /**
     * Recherche les articles en fonction du formulaire
     * @return array 
     */
    public function search($mots, $categorie, $limit, $offset, $orderBy){
        $query = $this->createQueryBuilder('a');
        if($mots != null){
            $query->where('MATCH_AGAINST(a.title, a.description) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        if($categorie != null){
            $query->leftJoin('a.categorie', 'c');
            $query->andWhere('c.id = :id')
                ->setParameter('id', $categorie->getId());
        }
        if($limit !== null && $offset!==null){
            $query->setMaxResults($limit)
                ->setFirstResult($offset);
        }
        $query->addOrderBy('a.createdAt', $orderBy);

        return $query->getQuery()->getResult();
    }
}
