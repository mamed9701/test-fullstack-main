<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\ClockingRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClockingRepository::class)]
class Clocking
{

    /**
     * Propriété de Type Projet de la rélation
     * Entre Clocking et Projet
     */
    #[ORM\ManyToOne(inversedBy: 'clockings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project           $clockingProject = null;

    /**
     * Collection d'objets de type User
     * Propriété de relation
     * Inversé par la propriété 'clockings'
     * dans l'entité User
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'clockings')]
    #[ORM\JoinColumn(nullable: false)]
    private Collection              $clockingUsers;

    /**
     * Durée passée sur le chantier
     */
    #[ORM\Column(type: Types::INTEGER)]
    private ?int               $duration        = null;

    /**
     * Date du pointage
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\DateTime('d/m/Y')]
    private ?DateTimeInterface $date = null;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int               $id              = null;


    public function __construct()
    {
        $this->clockingUsers = new ArrayCollection();
    }

    public function getClockingProject() : ?Project
    {
        return $this->clockingProject;
    }

    public function setClockingProject(?Project $clockingProject) : static
    {
        $this->clockingProject = $clockingProject;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getClockingUsers(): Collection
    {
        return $this->clockingUsers;
    }

    public function addClockingUser(User $user): self
    {
        if (!$this->clockingUsers->contains($user)) {
            $this->clockingUsers[] = $user;
            $user->addClocking($this);
        }

        return $this;
    }

    public function removeClockingUser(User $user): self
    {
        if($this->clockingUsers->removeElement($user)) {
            if($user->getClockings()->contains($this)){
                $user->removeClocking($this);
            }
        }

        return $this;
    }

    public function getDate() : ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date) : void
    {
        $this->date = $date;
    }

    public function getDuration() : ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration) : void
    {
        $this->duration = $duration;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

}
