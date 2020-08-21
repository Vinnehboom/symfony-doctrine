<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teacher")
 */
class TeacherController extends AbstractController
{
    private TeacherRepository $teacherRepository;

    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * @Route("/", name="teacher_index", methods={"GET"})
     * @param TeacherRepository $teacherRepository
     * @return JsonResponse
     */
    public function index(TeacherRepository $teacherRepository): JsonResponse
    {
        $teachers = $this->teacherRepository->findAll();
        $data = [];
        foreach($teachers as $teacher)
        {
            $data[] = [
                'id' => $teacher->getId(),
                'name' => $teacher->getName(),
                'email' => $teacher->getEmail(),
                'address' => $teacher->getAddress()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="teacher_new", methods={"GET","POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $email = $data['email'];
        $address = $data['address'];

        if (empty($name) || empty($email) || empty($address)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->teacherRepository->saveTeacher($name, $email, $address);
        return new JsonResponse(['status' => 'teacher created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="teacher_show", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $teacher = $this->teacherRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $teacher->getId(),
            'name' => $teacher->getName(),
            'email' => $teacher->getEmail(),
            'address' => $teacher->getAddress()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("/{id}", name="teacher_edit", methods={"PUT"})
     */
    public function update(Request $request, $id): JsonResponse
    {
        $teacher = $this->teacherRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $teacher->setName($data['name']);
        empty($data['email']) ? true : $teacher->setEmail($data['email']);
        empty($data['address']) ? true : $teacher->setAddress($data['address']);

        $updatedTeacher = $this->teacherRepository->updateTeacher($teacher);

        return new JsonResponse($updatedTeacher->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="teacher_delete", methods={"DELETE"})
     */
    public
    function delete($id): JsonResponse
    {
        $teacher = $this->teacherRepository->findOneBy(['id' => $id]);

        $this->teacherRepository->removeTeacher($teacher);

        return new JsonResponse(['status' => 'Teacher deleted'], Response::HTTP_NO_CONTENT);

    }
}
