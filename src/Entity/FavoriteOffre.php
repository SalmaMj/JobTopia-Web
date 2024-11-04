<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\favoriteOffreRepository")
 */
class FavoriteOffre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Users::class)
     * @ORM\JoinColumn(name="freelancerID", referencedColumnName="id")
     */
    private $freelancer;
   
    /**
     * @ORM\ManyToOne(targetEntity=Offres::class)
     * @ORM\JoinColumn(name="offreID", referencedColumnName="id")
     */
    private $offre;
    
       /**
     * @ORM\Column(type="boolean")
     */
    private $saved = false;

     /**
     * @ORM\Column(type="boolean")
     */
    private $removed = false;

    public function isSaved(): bool
    {
        return $this->saved;
    }

    public function setSaved(bool $saved): self
    {
        $this->saved = $saved;

        return $this;
    }
    public function isRemoved(): bool
    {
        return $this->removed;
    }

    public function setRemoved(bool $removed): self
    {
        $this->removed = $removed;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getFreelancer(): ?Users
    {
        return $this->freelancer;
    }
   
    public function getOffre(): ?Offres
    {
        return $this->offre;
    }
    
    public function setFreelancer(?Users $freelancer): self
    {
        $this->freelancer = $freelancer;
        return $this;
    }
    
    public function setOffre(?Offres $offre): self
    {
        $this->offre = $offre;
        return $this;
    }
}
