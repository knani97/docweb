<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Calendrier::class, mappedBy="uid")
     */
    private $calendriers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=RDV::class, mappedBy="patient")
     */
    private $rDVs;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=RDV::class, mappedBy="medecin")
     */
    private $rDVMed;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    public $specialite;
    public $diplome;
    public $cvtest;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\EqualTo(propertyPath="cpassword", message="mot de passe non identique")
     */
    private $password;
    /**
     *@Assert\EqualTo(propertyPath="password", message="mot de passe non identique")
     */
    public $cpassword;

    /**
     * @ORM\OneToOne(targetEntity=Cv::class, inversedBy="user", cascade={"persist", "remove"})
     */
    private $cv;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=Reagit::class, mappedBy="idUser")
     * @ORM\JoinColumn(nullable=true)
     */
    private $reagits;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="idUser")
     * @ORM\JoinColumn(nullable=true)
     */
    private $commentaires;

    public function __construct()
    {
        $this->calendriers = new ArrayCollection();
        $this->rDVs = new ArrayCollection();
        $this->rDVMed = new ArrayCollection();
        $this->reagits = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Calendrier[]
     */
    public function getCalendriers(): Collection
    {
        return $this->calendriers;
    }

    public function addCalendrier(Calendrier $calendrier): self
    {
        if (!$this->calendriers->contains($calendrier)) {
            $this->calendriers[] = $calendrier;
            $calendrier->setUid($this);
        }

        return $this;
    }

    public function removeCalendrier(Calendrier $calendrier): self
    {
        if ($this->calendriers->removeElement($calendrier)) {
            // set the owning side to null (unless already changed)
            if ($calendrier->getUid() === $this) {
                $calendrier->setUid(null);
            }
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|RDV[]
     */
    public function getRDVs(): Collection
    {
        return $this->rDVs;
    }

    public function addRDV(RDV $rDV): self
    {
        if (!$this->rDVs->contains($rDV)) {
            $this->rDVs[] = $rDV;
            $rDV->setPatient($this);
        }

        return $this;
    }

    public function removeRDV(RDV $rDV): self
    {
        if ($this->rDVs->removeElement($rDV)) {
            // set the owning side to null (unless already changed)
            if ($rDV->getPatient() === $this) {
                $rDV->setPatient(null);
            }
        }

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNom().' '.$this->getPrenom();
    }

    /**
     * @return Collection|RDV[]
     */
    public function getRDVMed(): Collection
    {
        return $this->rDVMed;
    }

    public function addRDVMed(RDV $rDVMed): self
    {
        if (!$this->rDVMed->contains($rDVMed)) {
            $this->rDVMed[] = $rDVMed;
            $rDVMed->setMedecin($this);
        }

        return $this;
    }

    public function removeRDVMed(RDV $rDVMed): self
    {
        if ($this->rDVMed->removeElement($rDVMed)) {
            // set the owning side to null (unless already changed)
            if ($rDVMed->getMedecin() === $this) {
                $rDVMed->setMedecin(null);
            }
        }

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }

    /**
     * @param mixed $specialite
     */
    public function setSpecialite($specialite): void
    {
        $this->specialite = $specialite;
    }

    /**
     * @return mixed
     */
    public function getDiplome()
    {
        return $this->diplome;
    }

    /**
     * @param mixed $diplome
     */
    public function setDiplome($diplome): void
    {
        $this->diplome = $diplome;
    }

    /**
     * @return mixed
     */
    public function getCvtest()
    {
        return $this->cvtest;
    }

    /**
     * @param mixed $cvtest
     */
    public function setCvtest($cvtest): void
    {
        $this->cvtest = $cvtest;
    }

    /**
     * @return mixed
     */
    public function getCpassword()
    {
        return $this->cpassword;
    }

    /**
     * @param mixed $cpassword
     */
    public function setCpassword($cpassword): void
    {
        $this->cpassword = $cpassword;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function getCv(): ?Cv
    {
        return $this->cv;
    }

    public function setCv(?Cv $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setIdUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getIdUser() === $this) {
                $article->setIdUser(null);
            }
        }

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
            $reagit->setIdUser($this);
        }

        return $this;
    }

    public function removeReagit(Reagit $reagit): self
    {
        if ($this->reagits->removeElement($reagit)) {
            // set the owning side to null (unless already changed)
            if ($reagit->getIdUser() === $this) {
                $reagit->setIdUser(null);
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
            $commentaire->setIdUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdUser() === $this) {
                $commentaire->setIdUser(null);
            }
        }

        return $this;
    }
}
