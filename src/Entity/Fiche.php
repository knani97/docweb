<?php

namespace App\Entity;

use App\Repository\FicheRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FicheRepository::class)
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
     * @ORM\OneToOne(targetEntity=Medicament::class, cascade={"persist", "remove"})
     */
    private $id_med;


    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $nom_commerciale;

    /**
     * @ORM\Column(type="integer",length=255,nullable=true)
     */
    private $dosage;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $utilisation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdMed(): ?Medicament
    {
        return $this->id_med;
    }

    public function setIdMed(?Medicament $id_med): self
    {
        $this->id_med = $id_med;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomCommerciale()
    {
        return $this->nom_commerciale;
    }

    /**
     * @param mixed $nom_commerciale
     */
    public function setNomCommerciale($nom_commerciale): void
    {
        $this->nom_commerciale = $nom_commerciale;
    }

    /**
     * @return mixed
     */
    public function getDosage()
    {
        return $this->dosage;
    }

    /**
     * @param mixed $dosage
     */
    public function setDosage($dosage): void
    {
        $this->dosage = $dosage;
    }

    /**
     * @return mixed
     */
    public function getUtilisation()
    {
        return $this->utilisation;
    }

    /**
     * @param mixed $utilisation
     */
    public function setUtilisation($utilisation): void
    {
        $this->utilisation = $utilisation;
    }

}
