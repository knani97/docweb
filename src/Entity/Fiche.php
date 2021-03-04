<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FicheRepository")
 */
class Fiche
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $nom_commercial;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dosage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $forme;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $classe_therapeutique;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $utilisation;

    /**
     * @ORM\OneToOne(targetEntity=Medicament::class, mappedBy="fiche", cascade={"persist", "remove"})
     */
    private $medicament;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCommercial(): ?string
    {
        return $this->nom_commercial;
    }

    public function setNomCommercial(string $nom_commercial): self
    {
        $this->nom_commercial = $nom_commercial;

        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(string $dosage): self
    {
        $this->dosage = $dosage;

        return $this;
    }

    public function getForme(): ?string
    {
        return $this->forme;
    }

    public function setForme(?string $forme): self
    {
        $this->forme = $forme;

        return $this;
    }

    public function getClasseTherapeutique(): ?string
    {
        return $this->classe_therapeutique;
    }

    public function setClasseTherapeutique(?string $classe_therapeutique): self
    {
        $this->classe_therapeutique = $classe_therapeutique;

        return $this;
    }

    public function getUtilisation(): ?string
    {
        return $this->utilisation;
    }

    public function setUtilisation(string $utilisation): self
    {
        $this->utilisation = $utilisation;

        return $this;
    }

    public function getMedicament(): ?Medicament
    {
        return $this->medicament;
    }

    public function setMedicament(Medicament $medicament): self
    {
        // set the owning side of the relation if necessary
        if ($medicament->getFiche() !== $this) {
            $medicament->setFiche($this);
        }

        $this->medicament = $medicament;

        return $this;
    }

    public function __toString()
    {
        return $this->getNomCommercial();
    }


}
