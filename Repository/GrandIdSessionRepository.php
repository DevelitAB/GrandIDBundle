<?php

namespace Bsadnu\GrandIDBundle\Repository;

use Bsadnu\GrandIDBundle\Entity\GrandIdSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrandIdSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrandIdSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrandIdSession[]    findAll()
 * @method GrandIdSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrandIdSessionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrandIdSession::class);
    }
}
