<?php

namespace App\Service;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;

class AccountService
{
	private AccountRepository $accountRepository;
	private EntityManagerInterface $entityManager;

	/**
	 * @param AccountRepository $accountRepository
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(AccountRepository $accountRepository, EntityManagerInterface $entityManager)
	{
		$this->accountRepository = $accountRepository;
		$this->entityManager = $entityManager;
	}

	/**
	 * @return Account[]
	 */
	public function getAll(): array
	{
		return $this->accountRepository->findAll();
	}

	/**
	 * @param int $id
	 * @return Account|null
	 */
	public function getById(int $id): ?Account
	{
		return $this->accountRepository->find($id);
	}

	/**
	 * @param Account $account
	 */
	public function save(Account $account): void
	{
		$this->entityManager->persist($account);
		$this->entityManager->flush();
	}

	/**
	 * @param int $id
	 * @return array
	 */
	public function getByOwner(int $id): array
	{
		return $this->accountRepository->findBy([
			'owner' => $id
		]);
	}
}
