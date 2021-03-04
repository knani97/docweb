<?php

namespace App\Entity;

use App\Repository\PharmacieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PharmacieRepository::class)
 */
class Pharmacie
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gerant;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephne;

    /**
     * @ORM\Column(type="time")
     */
    public $heure_ouverture;

    /**
     * @ORM\Column(type="time")
     */
    public $heure_fermeture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $type_pharmacie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getGerant(): ?string
    {
        return $this->gerant;
    }

    public function setGerant(string $gerant): self
    {
        $this->gerant = $gerant;

        return $this;
    }

    public function getTelephne(): ?int
    {
        return $this->telephne;
    }

    public function setTelephne(int $telephne): self
    {
        $this->telephne = $telephne;

        return $this;
    }

    public function getHeureOuverture(): ?\DateTimeInterface
    {
        $var= $this->heure_ouverture;
        return $var;
    }

    public function setHeureOuverture(\DateTimeInterface $heure_ouverture): self
    {
        $this->heure_ouverture = $heure_ouverture;

        return $this;
    }

    public function getHeureFermeture(): ?\DateTimeInterface
    {
        return $this->heure_fermeture;
    }

    public function setHeureFermeture(\DateTimeInterface $heure_fermeture): self
    {
        $this->heure_fermeture = $heure_fermeture;

        return $this;
    }

    public function getTypePharmacie(): ?string
    {
        return $this->type_pharmacie;
    }

    public function setTypePharmacie(string $type_pharmacie): self
    {
        $this->type_pharmacie = $type_pharmacie;

        return $this;
    }
}
