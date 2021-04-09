<?php

namespace App\Entity;

use App\Repository\RDVRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RDVRepository::class)
 */
class RDV
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity=Tache::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez choisisir un RDV!")
     */
    private $tacheDispo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rDVs")
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rDVMed")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medecin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $jointure;

    /**
     * @ORM\OneToOne(targetEntity=Tache::class, cascade={"persist", "remove"})
     */
    private $tacheUser;

    public function __construct()
    {
        $this->patient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTacheDispo(): ?Tache
    {
        return $this->tacheDispo;
    }

    public function setTacheDispo(Tache $tacheDispo): self
    {
        $this->tacheDispo = $tacheDispo;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getMedecin(): ?User
    {
        return $this->medecin;
    }

    public function setMedecin(?User $medecin): self
    {
        $this->medecin = $medecin;

        return $this;
    }

    public function getJointure(): ?string
    {
        return $this->jointure;
    }

    public function setJointure(?string $jointure): self
    {
        $this->jointure = $jointure;

        return $this;
    }

    public function getTacheUser(): ?Tache
    {
        return $this->tacheUser;
    }

    public function setTacheUser(?Tache $tacheUser): self
    {
        $this->tacheUser = $tacheUser;

        return $this;
    }
}
