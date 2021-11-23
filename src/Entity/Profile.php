<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use App\Traits\TimeStampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Profile
{
    use TimeStampTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $rs;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $url;

    /**
     * @ORM\OneToOne(targetEntity=Personne::class, mappedBy="profile", cascade={"persist", "remove"})
     */
    private $personne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRs(): ?string
    {
        return $this->rs;
    }

    public function setRs(string $rs): self
    {
        $this->rs = $rs;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPersonne(): ?Personne
    {
        return $this->personne;
    }

    public function setPersonne(Personne $personne): self
    {
        // set the owning side of the relation if necessary
        if ($personne->getProfile() !== $this) {
            $personne->setProfile($this);
        }

        $this->personne = $personne;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getRs().' : '.$this->getUrl();
    }


}
