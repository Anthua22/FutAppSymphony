<?php

namespace App\Repository;

use App\Entity\Partido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Partido|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partido|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partido[]    findAll()
 * @method Partido[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartidoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partido::class);
    }

    public function getEquipoByPartido(string $busqueda)
    {
        $qb = $this->createQueryBuilder('partido');
        $qb->innerJoin('partido.equipoLocal', 'equipo_local');
        $qb->innerJoin('partido.EquipoVisitante', 'equipo_visitante');


        $qb->where(
            $qb->expr()->like('equipo_local.nombre', ":busqueda")
        )->orWhere(
            $qb->expr()->like('equipo_visitante.nombre', ":busqueda")
        )->setParameter('busqueda', '%' . $busqueda . '%');


        return $qb->getQuery()->execute();
    }

    public function getUltimosPartidosAsignados(){
        $qb = $this->createQueryBuilder('partido');
        $qb->orderBy('partido.fecha_asignacion','DESC');
        $qb->setMaxResults(10);

        return $qb->getQuery()->execute();

    }
    // /**
    //  * @return Partido[] Returns an array of Partido objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Partido
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
