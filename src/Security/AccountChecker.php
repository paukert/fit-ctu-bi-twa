<?php

namespace App\Security;

use App\Entity\Account;
use DateTime;
use DateTimeZone;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Source and docs: https://symfony.com/doc/current/security/user_checkers.html
 */
class AccountChecker implements UserCheckerInterface
{
	public function checkPreAuth(UserInterface $account): void
	{
		if (!$account instanceof Account)
			return;

		if ($account->isPermanent())
			return;

		$today = new DateTime("today", new DateTimeZone('CET'));
		if ($account->getValidTo() < $today)
			throw new CustomUserMessageAccountStatusException('Your account is no longer valid.');
	}

	public function checkPostAuth(UserInterface $user): void
	{
		// No additional checks required
	}
}
