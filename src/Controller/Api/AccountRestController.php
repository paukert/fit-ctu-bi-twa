<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class AccountRestController extends AbstractFOSRestController
{
	/**
	 * @Route("/employees/{id}/accounts", requirements={"id": "\d+"}, methods={"GET"})
	 *
	 * @param Employee $employee
	 * @return Response
	 */
	public function getEmployeeAccounts(Employee $employee): Response
	{
		$accounts = $employee->getAccounts();
		$view = $this->view($accounts, Response::HTTP_OK);

		return $this->handleView($view);
	}
}
