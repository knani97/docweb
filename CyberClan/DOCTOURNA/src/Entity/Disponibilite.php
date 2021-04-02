<?php

namespace App\Entity;

use App\Repository\DisponibiliteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DisponibiliteRepository::class)
 */
class Disponibilite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type(
     *      type = "datetime",
     *      message = "Date correcte !",
     * )
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "La date doit être supérieur à la date d'aujourd'hui."
     * )
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "Date correcte !",
     * )
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "La date doit être supérieur à la date d'aujourd'hui."
     * )
     * @Assert\Expression(
     *     "this.getEnddate() >= this.getStartdate()",
     *     message="La date fin doit être inférieur à la date début."
     * )
     */
    private $endDate;

    /**
     * @ORM\Column(type="time")
     */
    private $dureeRDV;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $dureePause;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getDureeRDV(): ?\DateTimeInterface
    {
        return $this->dureeRDV;
    }

    public function setDureeRDV(\DateTimeInterface $dureeRDV): self
    {
        $this->dureeRDV = $dureeRDV;

        return $this;
    }

    public function getDureePause(): ?\DateTimeInterface
    {
        return $this->dureePause;
    }

    public function setDureePause(?\DateTimeInterface $dureePause): self
    {
        $this->dureePause = $dureePause;

        return $this;
    }
}
