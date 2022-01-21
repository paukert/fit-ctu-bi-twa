<?php

namespace App\Security;

use App\Entity\Account;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class AccountVoter extends Voter
{
	const EDIT_ACCOUNT = 'edit_account';
	const VIEW_ACCOUNT = 'view_account';

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
		return in_array($attribute, [self::EDIT_ACCOUNT, self::VIEW_ACCOUNT]) && $subject instanceof Account;
	}

	/**
	 * @inheritDoc
	 */
	protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
	{
		$loggedInAccount = $token->getUser();

		if (!$loggedInAccount instanceof Account)
			return false;

		/** @var Account $account */
		$account = $subject;

		switch ($attribute) {
			case self::EDIT_ACCOUNT:
				return $this->canEditAccount($account, $loggedInAccount);
			case self::VIEW_ACCOUNT:
				return $this->canViewAccount($account, $loggedInAccount);
		}

		throw new \LogicException('This code should not be reached!');
	}

	private function canEditAccount(Account $account, Account $loggedInAccount): bool
	{
		return ($account->getOwner() === $loggedInAccount->getOwner() && // logged-in user can edit his/her accounts
				!$account->isPermanent() && // but it is allowed to edit only temporary accounts
				$loggedInAccount->isPermanent()) || // from permanent account
				$this->security->isGranted('ROLE_ADMIN'); // or user has to be admin
	}

	private function canViewAccount(Account $account, Account $loggedInAccount): bool
	{
		return ($account->getOwner() === $loggedInAccount->getOwner() && // logged-in user can view his/her accounts
				$loggedInAccount->isPermanent()) || // but only from permanent account
				$this->security->isGranted('ROLE_ADMIN'); // or user has to be admin
	}
}
