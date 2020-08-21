<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    private EntityManager $manager;

    /**
     * StudentRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManager $entityManager
     */
    public function __construct
    (
        ManagerRegistry $registry,
        EntityManager $entityManager
    )
    {
        parent::__construct($registry, Student::class);
        $this->manager = $entityManager;
    }

    public function saveStudent($firstName, $lastName, $teacher, $email, $address)
    {
        $newStudent = new Student();

        $newStudent
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setTeacher($teacher)
            ->setEmail($email)
            ->setAddress($address);

        $this->manager->persist($newStudent);
        $this->manager->flush();
    }

    public function updateStudent(Student $student) : Student
    {
        $this->manager->persist($student);
        $this->manager->flush();

        return $student;
    }

    public function removeStudent(Student $student)
    {
        $this->manager->remove($student);
        $this->manager->flush();

    }

    // /**
    //  * @return Student[] Returns an array of Student objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Student
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
