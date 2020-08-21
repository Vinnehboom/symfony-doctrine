<?php

namespace App\Repository;

use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Teacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teacher[]    findAll()
 * @method Teacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherRepository extends ServiceEntityRepository
{
    private EntityManager $manager;
    public function __construct(
        ManagerRegistry $registry,
        EntityManager $entityManager

    )
    {
        parent::__construct($registry, Teacher::class);
        $this->manager = $entityManager;

    }

    public function saveTeacher($name, $email, $address)
    {
        $newTeacher = new Teacher();

        $newTeacher
            ->setName($name)
            ->setEmail($email)
            ->setAddress($address);

        $this->manager->persist($newTeacher);
        $this->manager->flush();
    }

    /**
     * @param Teacher $teacher
     * @return Teacher
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateTeacher(Teacher $teacher) : Teacher
    {
        $this->manager->persist($teacher);
        $this->manager->flush();

        return $teacher;
    }

    /**
     * @param Teacher $teacher
     */
    public function removeTeacher(Teacher $teacher)
    {
        $this->manager->remove($teacher);
        $this->manager->flush();
    }
    // /**
    //  * @return Teacher[] Returns an array of Teacher objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Teacher
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
