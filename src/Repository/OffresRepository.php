<?php


namespace App\Repository;

use App\Entity\Offres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Etat;
use App\Entity\Statut;


/**
 * @method Offres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offres[]    findAll()
 * @method Offres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @ORM\Entity(repositoryClass=OffresRepository::class)

 */

class OffresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offres::class);
    }
    public function findByDisponible(): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.etat = :etat')
            ->setParameter('etat', Etat::DISPONIBLE)
            ->getQuery()
            ->getResult();
    }
    public function countDisponible(): int
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.etat = :etat')
            ->Andwhere('o.statut = :statut')
            ->setParameter('statut', Statut::ACCEPTED)
            ->setParameter('etat', Etat::DISPONIBLE)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function save(Offres $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function remove(Offres $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    
    }
    public function findBySearch($titre = null, $categorie = null, $clientId = null)
{
    $qb = $this->createQueryBuilder('o')
        ->orderBy('o.dl', 'DESC');

    if ($titre !== null) {
        $qb->andWhere('o.titre LIKE :titre')
           ->setParameter('titre', '%'.$titre.'%');
    }

    if ($categorie !== null) {
        $qb->andWhere('o.categorie = :categorie')
           ->setParameter('categorie', $categorie);
    }

    if ($clientId !== null) {
        $qb->andWhere('o.clientid = :clientid')
           ->setParameter('clientid', $clientId);
    }

    return $qb->getQuery()->getResult();
}

public function findOffreById($titre, $clientid): ?Offres
{
    return $this->createQueryBuilder('o')
        ->andWhere('o.titre = :titre')
        ->andWhere('o.clientid = :clientid')
        ->setParameter('titre', $titre)
        ->setParameter('clientid', $clientid)
        ->getQuery()
        ->getOneOrNullResult();
}
public function findByTitleAndCategory($titre, $categorie)
{
    $qb = $this->createQueryBuilder('o')
        ->andWhere('o.disponible = true');

    if ($titre !== null) {
        $qb->andWhere('o.titre LIKE :titre')
            ->setParameter('titre', '%'.$titre.'%');
    }

    if ($categorie !== null) {
        $qb->andWhere('o.categorie = :categorie')
            ->setParameter('categorie', $categorie);
    }

    return $qb->getQuery()->getResult();
}


//    /**
//     * @return Offres[] Returns an array of Offres objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offres
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
