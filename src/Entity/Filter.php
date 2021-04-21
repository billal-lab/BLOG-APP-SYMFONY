<?php

namespace App\Entity;


class Filter{

    /**
     * @var string
     */
    private $mots;

    /**
     * @var Category
     */
    private $categorie;

    public function getCategorie(): Category 
    {
        return $this->categorie;
    }

    public function setCategorie(?Category $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }  

    // public function getContent(): ?string
    // {
    //     return $this->content;
    // }

    // public function setContent(?string $content): self
    // {
    //     $this->content = $content;

    //     return $this;
    // }  
}