<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\Type\AccountType;
use App\Service\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
	private AccountService $accountService;

	/**
	 * @param AccountService $accountService
	 */
	public function __construct(AccountService $accountService)
	{
		$this->accountService = $accountService;
	}

	/**
	 * @Route("/create", name="account_create", defaults={"id": null})
	 * @Route("/{id}/edit", name="account_edit", requirements={"id": "\d+"})
	 *
	 * @param UserPasswordHasherInterface $passwordHasher
	 * @param Request $request
	 * @param int|null $id
	 * @return Response
	 */
	public function editOrCreateAccount(UserPasswordHasherInterface $passwordHasher, Request $request, ?int $id): Response
	{
		$account = $id ? $this->accountService->getById($id) : new Account();
		if ($account === null)
			return $this->redirectToRoute('account_create');

		if ($id)
			$this->denyAccessUnlessGranted('edit_account', $account);
		else
			$this->denyAccessUnlessGranted('ROLE_ADMIN'); // only admin can create new account

		$form = $this->createForm(AccountType::class, $account);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$account = $form->getData();
			$plaintextPassword = $account->getPassword();
			$hashedPassword = $passwordHasher->hashPassword($account, $plaintextPassword);
			$account->setPassword($hashedPassword);

			$this->accountService->save($account);

			return $this->redirectToRoute('employee_account', ['id' => $account->getOwner()->getId()]);
		}

		return $this->render($id ? 'account/edit.html.twig' : 'account/create.html.twig', [
			'form' => $form->createView(),
			'account' => $account
		]);
	}
}
