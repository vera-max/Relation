<?php

namespace App\Controller;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Student\Model\StudentDto;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

 #[Route('/api', name: 'api_', format:'json')]
 #[OA\Tag(name: 'Students')]

final class StudentController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/students', name: 'student_all', methods:['GET'])]
    public function getAll(): Response
    {
        $student = $this->entityManager->getRepository(Student::class);
       return $this->json($student->findAll());
    }
     #[Route('/students', name: 'student_delete', methods:['DELETE'])]
    public function delete( string $id): JsonResponse
    {
        $student = $this->entityManager->getRepository(Student::class);
        if (!$student){
         return new JsonResponse(['No student with this id was found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($student->find($id));
        $this->entityManager->flush();
        
        return new JsonResponse(['student deleted']);
    }
     #[Route('/students', name: 'student_create', methods:['POST'])]
    public function create( #[MapRequestPayload] StudentDto  $payload): JsonResponse
    {
       $student = new Student();

       $student->setName($payload->name);
       $student->setCourse($payload->course);
       $student->setDepartment($payload->department);
       $student->setCreatedAt(new \DateTimeImmutable);

       $this->entityManager->persist($student);
       $this->entityManager->flush();
        
       return new JsonResponse('Created Successfully' );
    }
     #[Route('/students/{id}', name: 'student_edit', methods:['PUT'])]
    public function update(string $id, #[MapRequestPayload] StudentDto  $payload): JsonResponse
    {
      
       $student = $this->entityManager->getRepository(Student::class)->find($id);
       
       $student->setName($payload->name);
       $student->setCourse($payload->course);
       $student->setDepartment($payload->department);
        $student->setUpdatedAt(new \DateTimeImmutable);
     

       $this->entityManager->persist($student);
       $this->entityManager->flush();
        
       return new JsonResponse('Updated Successfully' );

}
#[Route('/students/{id}', name: 'student', methods:['GET'])]
    public function get( string $id): Response
    {
        $student = $this->entityManager->getRepository(Student::class);
       return $this->json($student->find($id));
    }
}
