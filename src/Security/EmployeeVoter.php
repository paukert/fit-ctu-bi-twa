<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\Employee;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class EmployeeVoter extends Voter
{
	const EDIT_EMPLOYEE = 'edit_employee';
	const VIEW_EMPLOYEE = 'view_employee';
	const VIEW_EMPLOYEES_ACCOUNTS = 'view_employees_accounts';

	private Security $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	/**
	 * @inheritDoc
	 */
	protected function supports(string $attribute, $subject): bool
	{
		if (!in_array($attribute, [self::EDIT_EMPLOYEE, self::VIEW_EMPLOYEE, self::VIEW_EMPLOYEES_ACCOUNTS]) ||
			!$subject instanceof Employee)
			return false;
		return true;
	}

	/**
	 * @inheritDoc
	 */
	protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
	{
		$loggedInAccount = $token->getUser();

		if (!$loggedInAccount instanceof Account)
			return false;

		/** @var Employee $employee */
		$employee = $subject;

		switch ($attribute) {
			case self::EDIT_EMPLOYEE:
				return $this->canEditEmployee($employee, $loggedInAccount);
			case self::VIEW_EMPLOYEE:
				return $this->canViewEmployee();
			case self::VIEW_EMPLOYEES_ACCOUNTS:
				return $this->canViewEmployeesAccounts($employee, $loggedInAccount);
		}

		throw new \LogicException('This code should not be reached!');
	}

	private function canEditEmployee(Employee $employee, Account $loggedInAccount): bool
	{
		return ($employee === $loggedInAccount->getOwner() && $loggedInAccount->isPermanent()) ||
				$this->security->isGranted('ROLE_ADMIN');
	}

	private function canViewEmployee(): bool
	{
		return $this->security->isGranted('ROLE_USER');
	}

	private function canViewEmployeesAccounts(Employee $employee, Account $loggedInAccount): bool
	{
		return ($employee === $loggedInAccount->getOwner() && $loggedInAccount->isPermanent()) ||
				$this->security->isGranted('ROLE_ADMIN');
	}
}
