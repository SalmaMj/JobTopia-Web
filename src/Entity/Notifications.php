<?php



namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="App\Repository\NotificationsRepository")
 */
class Notifications
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
    private $msg;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean", options={"default": FALSE})
     */
    private $vu = FALSE;
    /**
     * @ORM\ManyToOne(targetEntity="Offres")
     * @ORM\JoinColumn(name="offreId", referencedColumnName="id")
     */
    private $offre;
    
    public function getOffre(): ?Offres
    {
        return $this->offre;
    }
    public function setOffre(?Offres $offre): self
    {
        $this->offre = $offre;
        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMsg(): ?string
    {
        return $this->msg;
    }

    public function setMsg(string $msg): self
    {
        $this->msg = $msg;

        return $this;
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

    public function isVu(): bool
    {
        return $this->vu;
    }

    public function setVu(bool $vu): self
    {
        $this->vu = $vu;

        return $this;
    }
    public function markAsViewed(): void
    {
        $this->vu = true;
    }

}

