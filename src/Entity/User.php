<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{

    /**
     * Collection d'objets de type Clocking
     * Propriété de relation
     * Mappé par la propriété 'clockingUsers'
     * dans l'entité Clocking
     */
    #[ORM\ManyToMany(targetEntity: Clocking::class, mappedBy: 'clockingUsers', orphanRemoval: true, cascade: ['persist'])]
    private Collection $clockings;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int       $id        = null;

    /**
     * Prénom du collaborateur
     */
    #[ORM\Column(length: 255)]
    private ?string    $firstName = null;
     /**
      * Nom du collaborateur 
      */
    #[ORM\Column(length: 255)]
    private ?string    $lastName  = null;

    /**
     * Matricule du collaborateur
     */
    #[ORM\Column(length: 255, unique: true)]
    private ?string    $matricule = null;

    public function __construct()
    {
        $this->clockings = new ArrayCollection();
    }

    public function addClocking(Clocking $clocking) : static
    {
        if(!$this->clockings->contains($clocking)) {
            $this->clockings->add($clocking);
            $clocking->addClockingUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Clocking>
     */
    public function getClockings() : Collection
    {
        return $this->clockings;
    }

    public function getFirstName() : ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName) : static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getLastName() : ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName) : static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getMatricule() : ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule) : void
    {
        $this->matricule = $matricule;
    }

    public function removeClocking(Clocking $clocking) : static
    {
        if($this->clockings->removeElement($clocking)) {
            // set the owning side to null (unless already changed)
            if($clocking->getClockingUsers()->contains($this)) {
                $clocking->removeClockingUser($this);
            }
        }

        return $this;
    }
    public function fullName() : ?string {
        return $this->lastName . ' ' . $this->firstName;
    }
}
