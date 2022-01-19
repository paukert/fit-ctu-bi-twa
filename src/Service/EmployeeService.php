<?php

namespace App\Service;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeService
{
	private EmployeeRepository $employeeRepository;
	private EntityManagerInterface $entityManager;

	/**
	 * @param EmployeeRepository $employeeRepository
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EmployeeRepository $employeeRepository, EntityManagerInterface $entityManager)
	{
		$this->employeeRepository = $employeeRepository;
		$this->entityManager = $entityManager;
	}

	/**
	 * @return Employee[]
	 */
	public function getAll(): array
	{
		return $this->employeeRepository->findAll();
	}

	/**
	 * @param int $id
	 * @return Employee|null
	 */
	public function getById(int $id): ?Employee
	{
		return $this->employeeRepository->find($id);
	}

	/**
	 * @param Employee $employee
	 */
	public function save(Employee $employee): void
	{
		$this->entityManager->persist($employee);
		$this->entityManager->flush();
	}

	/**
	 * @param Employee $employee
	 */
	public function delete(Employee $employee): void
	{
		// Remove employees roles first
		foreach ($employee->getRoles() as $role)
			$employee->removeRole($role);
		$this->save($employee);

		$this->entityManager->remove($employee);
		$this->entityManager->flush();
	}

	/**
	 * Returns employees who have some attribute that contains given query
	 *
	 * @param string $query
	 * @return Employee[]
	 */
	public function getByAnything(string $query): array
	{
		return $this->employeeRepository->findByAnything($query);
	}
}
