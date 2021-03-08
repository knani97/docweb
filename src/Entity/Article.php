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
     * @ORM\ManyToMany(targetEntity=ArticleCat::class, inversedBy="articles")
     */
    private $articlecat;

    /**
     * @ORM\Column(type="integer")
     */
    private $idUser;

    public function __construct()
    {
        $this->Articlecat = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
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

    /**
     * @return Collection|ArticleCat[]
     */
    public function getArticlecat(): Collection
    {
        return $this->Articlecat;
    }

    public function addArticlecat(ArticleCat $articlecat): self
    {
        if (!$this->Articlecat->contains($articlecat)) {
            $this->Articlecat[] = $articlecat;
        }

        return $this;
    }

    public function removeArticlecat(ArticleCat $articlecat): self
    {
        $this->Articlecat->removeElement($articlecat);

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }
}
