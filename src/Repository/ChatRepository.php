<?php

namespace App\Repository;

use App\Entity\Chat;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    public function getMessageOneChat(Usuario $receptor, Usuario $emisor)
    {

        $qb = $this->createQueryBuilder('chat');
        $qb->innerJoin('chat.emisor', 'emisor');
        $qb->innerJoin('chat.receptor', 'receptor');


        $qb->where('emisor.id = ' . $emisor->getId() . ' and receptor.id = ' . $receptor->getId() .
            ' or emisor.id = ' . $receptor->getId() . ' and receptor.id = ' . $emisor->getId())->orderBy('chat.fecha', 'ASC');

        return $qb->getQuery()->execute();
    }

    public function getRecibidos(Usuario $usuario)
    {
        $qb = $this->createQueryBuilder('chat');
        $qb->innerJoin('chat.receptor', 'receptor');
        $qb->innerJoin('chat.emisor','emisor');

        $qb->where('receptor.id = ' . $usuario->getId())->select('Distinct emisor.id')->orderBy('chat.fecha', 'DESC');

        return $qb->getQuery()->execute();

    }

    public function getEnviados(Usuario $usuario)
    {
        $qb = $this->createQueryBuilder('chat');
        $qb->innerJoin('chat.receptor', 'receptor');
        $qb->innerJoin('chat.emisor','emisor');

        $qb->where('emisor.id = ' . $usuario->getId())->select('Distinct receptor.id')->orderBy('chat.fecha', 'DESC');

        return $qb->getQuery()->execute();

    }

    public function getUsersRecibidosBusqueda(string $busqueda,int $emisor)
    {
        $qb = $this->createQueryBuilder('chat');
        $qb->innerJoin('chat.emisor', 'emisor');
        $qb->innerJoin('chat.receptor', 'receptor');


        $qb->where(
            $qb->expr()->like('emisor.nombre', ":busqueda")
        )->orWhere(
            $qb->expr()->like('emisor.apellidos', ":busqueda")
        )->andWhere('receptor.id = '.$emisor)
            ->select('emisor.id')->distinct()
            ->setParameter('busqueda', '%' . $busqueda . '%');


        return $qb->getQuery()->execute();
    }

    public function getUsersEnviadosBusqueda(string $busqueda,int $receptor)
    {
        $qb = $this->createQueryBuilder('chat');
        $qb->innerJoin('chat.emisor', 'emisor');
        $qb->innerJoin('chat.receptor', 'receptor');


        $qb->where(
            $qb->expr()->like('receptor.nombre', ":busqueda")
        )->orWhere(
            $qb->expr()->like('receptor.apellidos', ":busqueda")
        )->andWhere('emisor.id = '.$receptor)
            ->select('receptor.id')->distinct()
            ->setParameter('busqueda', '%' . $busqueda . '%');


        return $qb->getQuery()->execute();
    }


    // /**
    //  * @return Chat[] Returns an array of Chat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Chat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
