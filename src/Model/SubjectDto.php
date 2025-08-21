<?php
namespace Subject\Model;
use Symfony\Component\Validator\Constraints as Assert;

class SubjectDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        
        #[Assert\NotBlank]
        public string $studentId,

        #[Assert\NotBlank]
        public int $code
         
    ){

    }
}
?>