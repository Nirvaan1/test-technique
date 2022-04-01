<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * @Route("/", name="app_student_home")
     */
    public function home(StudentRepository $studentRepository): Response
    {
        $students = $studentRepository->findAll();

        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }

    /**
     * @Route("/student/add", name="app_student_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StudentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();

            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('app_student_home');
        }

        return $this->render('student/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/student/edit/{id}", name="app_student_edit")
     */
    public function edit(Student $student, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('app_student_home');
        }

        return $this->render('student/edit.html.twig', [
            'form' => $form->createView(),
            'student' => $student
        ]);
    }

}
