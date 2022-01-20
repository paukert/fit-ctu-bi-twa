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
	 * @param int $id
	 * @return Account|null
	 */
	public function getById(int $id): ?Account
	{
		return $this->accountRepository->find($id);
	}

	/**
	 * @param Account $account
	 * @return void
	 */
	public function save(Account $account): void
	{
		$this->entityManager->persist($account);
		$this->entityManager->flush();
	}
}
