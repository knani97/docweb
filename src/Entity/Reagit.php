<?php

namespace App\Entity;

use App\Repository\ReagitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReagitRepository::class)
 */
class Reagit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="reagits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUser;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="reagits")
     */
    private $idArt;

    /**
     * @ORM\Column(type="integer")
     */
    private $typeReact;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?Users
    {
        return $this->idUser;
    }

    public function setIdUser(?Users $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdArt(): ?Article
    {
        return $this->idArt;
    }

    public function setIdArt(?Article $idArt): self
    {
        $this->idArt = $idArt;

        return $this;
    }

    public function getTypeReact(): ?int
    {
        return $this->typeReact;
    }

    public function setTypeReact(int $typeReact): self
    {
        $this->typeReact = $typeReact;

        return $this;
    }
}
