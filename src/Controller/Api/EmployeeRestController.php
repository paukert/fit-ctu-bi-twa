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
	 */
	public function getAllEmployees(): Response
	{
		$employees = $this->employeeService->getAll();
		$view = $this->view($employees, 200);

		return $this->handleView($view);
	}

	/**
	 * @Route("/employees/{id}", requirements={"id": "\d+"}, methods={"GET"})
	 */
	public function getEmployee(int $id): Response
	{
		$employee = $this->employeeService->getById($id);
		$view = $this->view($employee, ($employee ? 200 : 404));

		return $this->handleView($view);
	}

	/**
	 * @Route("/employees/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
	 */
	public function deleteEmployee(int $id): Response
	{
		$employee = $this->employeeService->getById($id);
		if ($employee)
			$this->employeeService->delete($employee);

		$view = $this->view($employee, ($employee ? 200 : 404));
		return $this->handleView($view);
	}

	/**
	 * @Route("/employees/{id}", requirements={"id": "\d+"}, methods={"PUT"})
	 *
	 * @RequestParam(name="firstName")
	 * @RequestParam(name="lastName")
	 */
	public function editEmployee(int $id, Request $request): Response
	{
		$employee = $this->employeeService->getById($id);
		if (!$employee) {
			$view = $this->view(null, 404);
			return $this->handleView($view);
		}

		if (!$this->processEmployee($employee, $request)) {
			$view = $this->view($request->request->all(), 400);
			return $this->handleView($view);
		}

		$this->employeeService->save($employee);

		$view = $this->view($employee, 200);
		return $this->handleView($view);
	}

	/**
	 * @Route("/employees", methods={"POST"})
	 *
	 * @RequestParam(name="firstName")
	 * @RequestParam(name="lastName")
	 */
	public function createEmployee(Request $request): Response
	{
		$employee = new Employee();

		if (!$this->processEmployee($employee, $request)) {
			$view = $this->view($request->request->all(), 400);
			return $this->handleView($view);
		}

		$this->employeeService->save($employee);

		$view = $this->view($employee, 201);
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
