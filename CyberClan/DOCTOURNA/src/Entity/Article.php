<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
    private $titre;

    /**
     * @ORM\Column(type="string", length=900)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAjout;

    /**
     * @ORM\Column(type="integer")
     */
    private $etatAjout;

    /**
     * @ORM\Column(type="integer")
     */
    private $idUser;

    /**
     * @ORM\ManyToOne(targetEntity=ArticleCat::class, inversedBy="articles", cascade={"persist"})
     */
    private $idCat;

    /**
     * @ORM\OneToMany(targetEntity=Reagit::class, mappedBy="idArt")
     */
    private $reagits;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="idArt")
     */
    private $commentaires;

    public function __construct()
    {
        $this->reagits = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser): void
    {
        $this->idUser = $idUser;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    public function getEtatAjout(): ?int
    {
        return $this->etatAjout;
    }

    public function setEtatAjout(int $etatAjout): self
    {
        $this->etatAjout = $etatAjout;

        return $this;
    }

    public function getIdCat(): ?ArticleCat
    {
        return $this->idCat;
    }

    public function setIdCat(?ArticleCat $idCat): self
    {
        $this->idCat = $idCat;

        return $this;
    }

    /**
     * @return Collection|Reagit[]
     */
    public function getReagits(): Collection
    {
        return $this->reagits;
    }

    public function addReagit(Reagit $reagit): self
    {
        if (!$this->reagits->contains($reagit)) {
            $this->reagits[] = $reagit;
            $reagit->setIdArt($this);
        }

        return $this;
    }

    public function removeReagit(Reagit $reagit): self
    {
        if ($this->reagits->removeElement($reagit)) {
            // set the owning side to null (unless already changed)
            if ($reagit->getIdArt() === $this) {
                $reagit->setIdArt(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setIdArt($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdArt() === $this) {
                $commentaire->setIdArt(null);
            }
        }

        return $this;
    }







}
