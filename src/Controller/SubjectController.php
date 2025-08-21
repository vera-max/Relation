<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Subject\Model\SubjectDto;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

 #[Route('/api', name: 'api_', format:'json')]
 #[OA\Tag(name: 'Subjects')]

final class SubjectController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/students', name: 'subject_all', methods:['GET'])]
    public function getAll(): Response
    {
        $subject = $this->entityManager->getRepository(Subject::class);
       return $this->json($subject->findAll());
    }
     #[Route('/students', name: 'subject_delete', methods:['DELETE'])]
    public function delete( string $id): JsonResponse
    {
        $subject = $this->entityManager->getRepository(Subject::class);
        if ($subject){
         return new JsonResponse(['No student with this id was found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($subject->find($id));
        $this->entityManager->flush();
        
        return new JsonResponse(['subject deleted']);
    }
     #[Route('/students', name: 'subject_create', methods:['POST'])]
    public function create( #[MapRequestPayload] SubjectDto  $payload): JsonResponse
    {
        $subject = new Subject();
       $subject = $this->entityManager->getRepository(Subject::class);

      $StudentRepo=$this->entityManager->getRepository(Student::class);
      
      $Student=$StudentRepo->find($payload->student);
       $subject->setName($payload->name);
       $subject->setCode($payload->code);
       $subject->addstudent($Student);
      // $subject->setCreatedAt(new \DateTimeImmutable);

       $this->entityManager->persist($subject);
       $this->entityManager->flush();
        
       return new JsonResponse('Created Successfully' );
    }
     #[Route('/Subject{id}', name: 'subject_edit', methods:['PUT'])]
    public function update(string $id, #[MapRequestPayload] SubjectDto  $payload): JsonResponse
    {
        
       $subject = $this->entityManager->getRepository(Subject::class)->find($id);

      $StudentRepo=$this->entityManager->getRepository(Student::class);
      $Student=$StudentRepo->find($payload->student);
       $subject->setName($payload->name);
       $subject->setCode($payload->code);
       $subject->addStudent($Student);
       //$subject->setUpdatedAt(new \DateTimeImmutable);
     

       $this->entityManager->persist($subject);
       $this->entityManager->flush();
        
       return new JsonResponse('Updated Successfully' );

}
#[Route('/students{id}', name: 'subject', methods:['GET'])]
    public function get( string $id): Response
    {
        $subject = $this->entityManager->getRepository(Subject::class);
       return $this->json($subject->find($id));
    }
}

