<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @Route("/student")
 */
class StudentController extends AbstractController
{
    private StudentRepository $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * @Route("/", name="student_index", methods={"GET"})
     * @param StudentRepository $studentRepository
     * @return Response
     */
    public function index(StudentRepository $studentRepository): JsonResponse
    {
        $students = $this->studentRepository->findAll();
        $data = [];
        foreach($students as $student)
        {
            $data[] = [
                'id' => $student->getId(),
                'firstName' => $student->getFirstName(),
                'lastName' => $student->getLastName(),
                'teacher' => $student->getTeacher(),
                'email' => $student->getEmail(),
                'address' => $student->getAddress()
            ];

        }


        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="student_new", methods={"GET","POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $teacher = $data['teacher'];
        $email = $data['email'];
        $address = $data['address'];

        if (empty($firstName) || empty($lastName) || empty($email) || empty($teacher) || empty($address))
        {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->studentRepository->saveStudent($firstName, $lastName, $teacher, $email, $address);
        return new JsonResponse(['status' => 'student created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{address.id}", name="student_show", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $student = $this->studentRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $student->getId(),
            'firstName' => $student->getFirstName(),
            'lastName' => $student->getLastName(),
            'teacher' => $student->getTeacher(),
            'email' => $student->getEmail(),
            'address' => $student->getAddress()
            ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{address.id}", name="student_edit", methods={"PUT"})
     */
    public function update(Request $request, $id): JsonResponse
    {
        $student = $this->studentRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['firstName']) ? true : $student->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $student->setLastName($data['lastName']);
        empty($data['teacher']) ? true : $student->setTeacher($data['teacher']);
        empty($data['email']) ? true : $student->setEmail($data['email']);
        empty($data['address']) ? true : $student->setAddress($data['address']);

        $updatedStudent = $this->studentRepository->updateStudent($student);

        return new JsonResponse($updatedStudent->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/{address.id}", name="student_delete", methods={"DELETE"})
     */
    public
    function delete($id): JsonResponse
    {
        $student = $this->studentRepository->findOneBy(['id' => $id]);

        $this->studentRepository->removeStudent($student);

        return new JsonResponse(['status' => 'student deleted'], Response::HTTP_NO_CONTENT);

    }

}
