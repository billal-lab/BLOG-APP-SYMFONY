<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Cantact{

    /**
     * @var string
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @var string
     * @Assert\NotBlank
     */
    private $subject;

    public function getsubject(): ?string
    {
        return $this->subject;
    }

    public function setsubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }  

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }  
}