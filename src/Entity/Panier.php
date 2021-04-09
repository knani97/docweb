<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PanierRepository::class)
 */
class Panier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $somme;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="panier",cascade={"persist"})
     */
    private $idvid;

    public function __construct()
    {
        $this->idvid = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSomme(): ?float
    {
        return $this->somme;
    }

    public function setSomme(?float $somme): self
    {
        $this->somme = $somme;

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getIdvid(): Collection
    {
        return $this->idvid;
    }

    public function addIdvid(Video $idvid): self
    {
        if (!$this->idvid->contains($idvid)) {
            $this->idvid[] = $idvid;
            $idvid->setPanier($this);
        }

        return $this;
    }

    public function removeIdvid(Video $idvid): self
    {
        if ($this->idvid->removeElement($idvid)) {
            // set the owning side to null (unless already changed)
            if ($idvid->getPanier() === $this) {
                $idvid->setPanier(null);
            }
        }

        return $this;
    }
}
