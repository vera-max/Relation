<?php
namespace Student\Model;
use Symfony\Component\Validator\Constraints as Assert;

class StudentDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        
        #[Assert\NotBlank]
        public string $course,

        #[Assert\NotBlank]
        public string $department
         
    ){

    }
}
?>