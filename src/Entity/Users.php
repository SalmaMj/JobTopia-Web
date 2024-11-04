<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class Users
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=false)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="imagePath", type="string", length=255, nullable=false)
     */
    private $imagepath;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="categorie", type="string", length=255, nullable=true)
     */
    private $categorie;

    /**
     * @var float|null
     *
     * @ORM\Column(name="salaire", type="float", precision=10, scale=0, nullable=true)
     */
    private $salaire;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cv", type="string", length=255, nullable=true)
     */
    private $cv;

    /**
     * @var float|null
     *
     * @ORM\Column(name="note", type="float", precision=10, scale=0, nullable=true)
     */
    private $note;

    /**
     * @var int
     *
     * @ORM\Column(name="nbAvis", type="integer", nullable=false)
     */
    private $nbavis = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     */
    private $role;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profession", type="string", length=255, nullable=true)
     */
    private $profession;

    /**
     * @var bool
     *
     * @ORM\Column(name="IsDeleted", type="boolean", nullable=false)
     */
    private $isdeleted;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Code", type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @var bool
     *
     * @ORM\Column(name="Confirmed", type="boolean", nullable=false)
     */
    private $confirmed;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codePassword", type="string", length=255, nullable=true)
     */
    private $codepassword;
    
    
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

        public function __toString(): string
    {
        return $this->getId() ?: '';
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getImagepath(): ?string
    {
        return $this->imagepath;
    }

    public function setImagepath(string $imagepath): self
    {
        $this->imagepath = $imagepath;

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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(?float $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getNbavis(): ?int
    {
        return $this->nbavis;
    }

    public function setNbavis(int $nbavis): self
    {
        $this->nbavis = $nbavis;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function isIsdeleted(): ?bool
    {
        return $this->isdeleted;
    }

    public function setIsdeleted(bool $isdeleted): self
    {
        $this->isdeleted = $isdeleted;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function isConfirmed(): ?bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    public function getCodepassword(): ?string
    {
        return $this->codepassword;
    }

    public function setCodepassword(?string $codepassword): self
    {
        $this->codepassword = $codepassword;

        return $this;
    }


}
