<?php

namespace App\Controller\Api;

use App\Service\AccountService;
use App\Service\EmployeeService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class AccountRestController extends AbstractFOSRestController
{
	private AccountService $accountService;
	private EmployeeService $employeeService;

	/**
	 * @param AccountService $accountService
	 * @param EmployeeService $employeeService
	 */
	public function __construct(AccountService $accountService, EmployeeService $employeeService)
	{
		$this->accountService = $accountService;
		$this->employeeService = $employeeService;
	}

	/**
	 * @Route("/employees/{id}/accounts", requirements={"id": "\d+"}, methods={"GET"})
	 */
	public function getEmployeeAccounts(int $id): Response
	{
		$employee = $this->employeeService->getById($id);
		if (!$employee) {
			$view = $this->view(null, 404);
			return $this->handleView($view);
		}

		$accounts = $this->accountService->getByOwner($id);
		$view = $this->view($accounts, 200);

		return $this->handleView($view);
	}
}
