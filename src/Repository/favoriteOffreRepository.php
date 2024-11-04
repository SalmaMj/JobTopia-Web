<?php

namespace App\Repository;

use App\Entity\FavoriteOffre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FavoriteOffre|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteOffre|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteOffre[]    findAll()
 * @method FavoriteOffre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class favoriteOffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteOffre::class);
    }

   
}