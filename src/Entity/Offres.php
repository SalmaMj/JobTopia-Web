<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;
use App\Entity\Etat;
use App\Entity\Statut;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="offres")
 * @ORM\Entity(repositoryClass="App\Repository\OffresRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Offres

{
    private $entityManager;

    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
    * @Assert\NotBlank(message="La description ne doit pas être vide.")
     * @Assert\Length(min=10, minMessage="La description doit comporter au moins {{ limit }} caractères.")
     */
    private ?string $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
      *@Assert\NotBlank(message="Date limite obligatoire")
     * @Assert\Expression(
     * "this.getDl() > this.getDc()",
     * message="La date limite doit être postérieure à la date de création"
     * )
     */
    private ?\DateTimeInterface $dl = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $dc = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *  @Assert\NotBlank(message="Il faut choisir une catégorie")
     */
    private ?string $categorie = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
     private $etat = Etat::DISPONIBLE; 


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $role = Role::CLIENT; 

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le titre ne peut pas être vide.")
     * @Assert\Length(
     *      min = 5,
     *      max = 20,
     *      minMessage = "Le titre doit contenir au moins {{ limit }} caractères",
     *      maxMessage = "Le titre ne peut pas contenir plus de {{ limit }} caractères"
     * )
      *@Assert\Regex(
        *     pattern="/^[a-zA-Z\s]+$/",
        *     message="Le titre doit contenir uniquement des lettres."
        * )
     */
    private ?string $titre = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
      * @Assert\Length(max=10, maxMessage="La compétence 1 ne doit pas dépasser {{ limit }} caractères.")
      * @Assert\NotBlank(message="La compétence1 ne doit pas être vide.")
     */
    private ?string $skill1 = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
      * @Assert\Length(max=10, maxMessage="La compétence 2 ne doit pas dépasser {{ limit }} caractères.")
      * @Assert\NotBlank(message="La compétence2  ne doit pas être vide.")
     */
    private ?string $skill2 = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
      * @Assert\Length(max=10, maxMessage="La compétence 3 ne doit pas dépasser {{ limit }} caractères.")
      * @Assert\NotBlank(message="La compétence3 ne doit pas être vide.")
       
     */
    private ?string $skill3 = null;

   /**
     * @ORM\Column(name="logoPath", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Il faut mettre un LOGO.")
     */
    private $logoPath;
     /**
         * @ORM\Column(type="string", length=10, nullable=true)
         */
        private ?string $statut = null;
        /**
         * @ORM\ManyToOne(targetEntity=Users::class)
         * @ORM\JoinColumn(name="clientID", referencedColumnName="id")
         * @Assert\NotBlank(message="L'id de client ne doit pas être vide.")
         */
    private $clientid;
       
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        $this->description = strip_tags($description);
        return $this;

        return $this;
    }

    public function getDl(): ?\DateTimeInterface
    {
        return $this->dl;
    }

    public function setDl(\DateTimeInterface $dl): self
    {
        $this->dl = $dl;

        return $this;
    }
     /**
       * Retourne le nombre de jours depuis la création de l'offre
       *
       * @return int
     */
    public function getDaysSinceCreated(): String
    {
        $now = new \DateTime();
        $diff = $this->dc->diff($now);
        
        if ($diff->days > 0) {
            return sprintf('%d jours', $diff->days);
        } elseif ($diff->h > 0) {
            return sprintf('%d heures', $diff->h);
        } else {
            return sprintf('%d minutes', $diff->i);
        }
    }
    

    public function getDc(): ?\DateTimeInterface
    {
        return $this->dc;
    }

    public function setDc(\DateTimeInterface $dc): self
    {
        $this->dc = $dc;

        return $this;
    }
    public function getCategorie(): ?string
    {
        return $this->categorie;
    }
    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }
    public function setEtat(string $etat): self
    {
        $this->etat = Etat::fromValue($etat);   
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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSkill1(): ?string
    {
        return $this->skill1;
    }

    public function setSkill1(string $skill1): self
    {
        $this->skill1 = $skill1;

        return $this;
    }

    public function getSkill2(): ?string
    {
        return $this->skill2;
    }

    public function setSkill2(string $skill2): self
    {
        $this->skill2 = $skill2;

        return $this;
    }

    public function getSkill3(): ?string
    {
        return $this->skill3;
    }

    public function setSkill3(string $skill3): self
    {
        $this->skill3 = $skill3;

        return $this;
    }

    public function getLogoPath(): ?string
    {
        return $this->logoPath;
    }

    public function setLogoPath(?string $logoPath): self
    {
        $this->logoPath = $logoPath;

        return $this;
    }
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->dc = new \DateTime();
    }
    public function getClientid(): ?Users
    {
        return $this->clientid;
    } 
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->dc = new DateTimeImmutable();
        $this->accepted = false;
        $this->favorites = new ArrayCollection();
    }
    

    public function setClientid($clientid): self
    {
        if ($clientid instanceof Users) {
            $this->clientid = $clientid;
        } elseif (is_string($clientid)) {
            $this->clientid = $this->entityManager->getRepository(Users::class)->find($clientid);
        } elseif (is_int($clientid)) {
            $this->clientid = $this->entityManager->getRepository(Users::class)->find($clientid);
        } else {
            throw new \InvalidArgumentException('$clientid doit être une instance de Users, une chaîne de caractères ou une valeur entière');
        }
        return $this;
    }
    
    public function isExpired(): bool
    {
        return $this->dl < new \DateTime();
    }    
    
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
    
        return $this;
    }
    
    public function __toString(): string
    {
        return $this->getId() ?: '';
    }

   
}
