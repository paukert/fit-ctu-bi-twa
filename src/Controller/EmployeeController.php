<?php
/**
 * Inspired by David Bernhauer <bernhdav@fit.cvut.cz>
 * https://gitlab.fit.cvut.cz/BI-TWA/example/blob/cv04/src/Controller/ArticleController.php
 */

namespace App\Controller;

use App\Entity\Employee;
use App\Form\Type\EmployeeType;
use App\Service\EmployeeService;
use App\Service\FileManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/employee")
 */
class EmployeeController extends AbstractController
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
	 * @Route("/", name="employee_list")
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function listEmployees(Request $request): Response
	{
		$term = $request->request->get('search', '');
		return $this->render('employee/list.html.twig', [
			'employees' => $this->employeeService->getByAnything($term),
		]);
	}

	/**
	 * @Route("/{id}", name="employee_detail", requirements={"id": "\d+"})
	 *
	 * @param Employee $employee
	 * @return Response
	 */
	public function showEmployeeDetail(Employee $employee): Response
	{
		$this->denyAccessUnlessGranted('view_employee', $employee);

		return $this->render('employee/detail.html.twig', [
			'employee' => $employee,
		]);
	}

	/**
	 * @Route("/{id}/account", name="employee_account", requirements={"id": "\d+"})
	 *
	 * @param Employee $employee
	 * @return Response
	 */
	public function listEmployeesAccounts(Employee $employee): Response
	{
		$this->denyAccessUnlessGranted('view_employees_accounts', $employee);

		return $this->render('employee/account.html.twig', [
			'employee' => $employee,
		]);
	}

	/**
	 * @Route("/create", name="employee_create", defaults={"id": null})
	 * @Route("/{id}/edit", name="employee_edit", requirements={"id": "\d+"})
	 *
	 * @param Request $request
	 * @param FileManager $fileManager
	 * @param int|null $id
	 * @return Response
	 */
	public function editOrCreateEmployee(Request $request, FileManager $fileManager, ?int $id): Response
	{
		$employee = $id ? $this->employeeService->getById($id) : new Employee();
		if ($employee === null)
			return $this->redirectToRoute('employee_create');

		if ($id)
			$this->denyAccessUnlessGranted('edit_employee', $employee);
		else
			$this->denyAccessUnlessGranted('ROLE_ADMIN'); // only admin can create new employee

		$form = $this->createForm(EmployeeType::class, $employee);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$imageFile = $form->get('image')->getData();
			if ($imageFile) {
				$imageFileName = $fileManager->upload($imageFile);
				if ($imageFileName) {
					$fileManager->delete($employee->getImageFileName());
					$employee->setImageFileName($imageFileName);
				}
			}

			$this->employeeService->save($employee);
			return $this->redirectToRoute('employee_detail', ['id' => $employee->getId()]);
		}

		return $this->render($id ? 'employee/edit.html.twig' : 'employee/create.html.twig', [
			'form' => $form->createView(),
			'employee' => $employee
		]);
	}
}
