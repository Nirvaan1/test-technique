<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/department", name="app_department")
     */
    public function index(DepartmentRepository  $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();

        return $this->render('department/index.html.twig', [
            'departments' => $departments,
        ]);
    }

    /**
     * @Route("/department/add", name="app_department_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(DepartmentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $department = $form->getData();

            $entityManager->persist($department);
            $entityManager->flush();

            return $this->redirectToRoute('app_department');
        }
        return $this->render('department/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/department/edit/{id}", name="app_department_edit")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, Department $department): Response
    {
        $form = $this->createForm(DepartmentType::class, $department);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($department);
            $entityManager->flush();

            return $this->redirectToRoute('app_department');
        }
        return $this->render('department/edit.html.twig', [
            'form' => $form->createView(),
            'department' => $department
        ]);
    }

    /**
     * @Route("/department/delete/{id}", name="app_department_delete")
     */
    public function delete( EntityManagerInterface $entityManager, Department $department): Response
    {
        $entityManager->remove($department);
        $entityManager->flush();

        return $this->redirectToRoute('app_department');
    }
}
