<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Form\Type\EmployeeType;
use App\Service\EmployeeService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class EmployeeRestController extends AbstractFOSRestController
{
	private EmployeeService $employeeService;

	/**
	 * @param EmployeeService $employeeService
	 */
	public function __construct(EmployeeService $employeeService)
	{
		$this->employeeService = $employeeService;
	}

	/**
	 * @Route("/employees", methods={"GET"})
	 *
	 * @return Response
	 */
	public function getAllEmployees(): Response
	{
		$employees = $this->employeeService->getAll();
		$view = $this->view($employees, Response::HTTP_OK);
		return $this->handleView($view);
	}

	/**
	 * @Route("/employees/{id}", requirements={"id": "\d+"}, methods={"GET"})
	 *
	 * @param Employee $employee
	 * @return Response
	 */
	public function getEmployee(Employee $employee): Response
	{
		$view = $this->view($employee, Response::HTTP_OK);
		return $this->handleView($view);
	}

	/**
	 * @Route("/employees/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
	 *
	 * @param Employee $employee
	 * @return Response
	 */
	public function deleteEmployee(Employee $employee): Response
	{
		$this->employeeService->delete($employee);
		$view = $this->view($employee, Response::HTTP_OK);
		return $this->handleView($view);
	}

	/**
	 * @Route("/employees/{id}", requirements={"id": "\d+"}, methods={"PUT"})
	 *
	 * @RequestParam(name="firstName")
	 * @RequestParam(name="lastName")
	 *
	 * @param Employee $employee
	 * @param Request $request
	 * @return Response
	 */
	public function editEmployee(Employee $employee, Request $request): Response
	{
		if (!$this->processEmployee($employee, $request))
			return $this->handleView($this->view($request->request->all(), Response::HTTP_BAD_REQUEST));

		$this->employeeService->save($employee);
		$view = $this->view($employee, Response::HTTP_OK);

		return $this->handleView($view);
	}

	/**
	 * @Route("/employees", methods={"POST"})
	 *
	 * @RequestParam(name="firstName")
	 * @RequestParam(name="lastName")
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function createEmployee(Request $request): Response
	{
		$employee = new Employee();

		if (!$this->processEmployee($employee, $request))
			return $this->handleView($this->view($request->request->all(), Response::HTTP_BAD_REQUEST));

		$this->employeeService->save($employee);
		$view = $this->view($employee, Response::HTTP_CREATED);

		return $this->handleView($view);
	}

	/**
	 * @param Employee $employee
	 * @param Request $request
	 * @return bool
	 */
	public function processEmployee(Employee &$employee, Request &$request): bool
	{
		$form = $this->createForm(
			EmployeeType::class, $employee, [
			'csrf_protection' => false,
		])->submit($request->request->all());

		return $form->isSubmitted() && $form->isValid();
	}
}
