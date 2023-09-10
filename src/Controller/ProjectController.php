<?php

namespace App\Controller;

use App\Model\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
/**
 * Summary of ProjectController
 */
class ProjectController extends AbstractController
{
    #[Route('/employees', name: 'employees_index', methods:['GET'] )]
    /**
     * Get all employees
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $employees = Employee::get('', 'Created, ID ASC');
        } catch (\Throwable $th) {
            throw $th;
        }

        return $this->json($employees);
    }

    #[Route('/employees/{id}', name: 'employees_getone', methods:['GET'] )]
    /**
     * Get one employee
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function get_one(Request $request, int $id): JsonResponse
    {
        $employee = [];

        if (!empty($id)) {
            try {
                $employee = Employee::get('ID = '. $id);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        return $this->json($employee);
    }


    #[Route('/employees', name: 'employees_create', methods:['POST'] )]
    /**
     * Create employees
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $employees = $request->request->get('employees');
        if (!empty($employees)) {
            $employees = json_decode($employees);

            foreach ($employees as $employeeData) {
                $employee = new Employee();
                $employee->CompanyName = $employeeData[0];
                $employee->EmployeeName = $employeeData[1];
                $employee->Email = $employeeData[2];
                $employee->Salary = $employeeData[3];

                try {
                    $employee->write();
                } catch (\Throwable $th) {
                    throw $th;
                }
            }

            return $this->json(true);
        }
        return $this->json(false);
    }

    #[Route('/employees/{id}', name: 'employees_update', methods:['PUT', 'PATCH'] )]
    /**
     * Update employee
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $email = $request->request->get('email');
        if (!empty($email)) {
            if (!empty($id)) $employee = Employee::get('ID = '. $id)[0];

            if (!empty($employee)) {
                // Ideally we'd already get employees instanciated with the Employee dataobject
                $employee = new Employee();
                $employee->ID = $id;
                $employee->Email = $email;

                try {
                    $employee->write();
                } catch (\Throwable $th) {
                    throw $th;
                }

                return $this->json(true);
         }
        }
        return $this->json(false);
    }
}
