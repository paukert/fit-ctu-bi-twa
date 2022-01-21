<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Employee::class);
	}

	/**
	 * Returns employees who have some attribute that contains given query
	 *
	 * @param string $query
	 * @return Employee[]
	 */
	public function findByAnything(string $query): array
	{
		return $this->createQueryBuilder('e')
			->leftJoin('e.roles', 'r')
			->andWhere('e.firstName LIKE :val OR
						e.lastName LIKE :val OR
						e.mail LIKE :val OR
						e.phone LIKE :val OR
						e.web LIKE :val OR
						e.description LIKE :val OR
						r.title LIKE :val OR
						r.description LIKE :val')
			->setParameter('val', '%' . $query . '%')
			->addOrderBy('e.lastName', 'ASC')
			->addOrderBy('e.firstName', 'ASC')
			->getQuery()
			->getResult();
	}
}
