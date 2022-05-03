<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Studio::class, inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private $rightsowner;

    #[ORM\Column(type: 'float')]
    private $royalty;

    #[ORM\Column(type: 'integer')]
    private $viewings;

    public function __construct()
    {
        $this->royalty = 0;
        $this->viewings = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRightsowner(): ?Studio
    {
        return $this->rightsowner;
    }

    public function setRightsowner(?Studio $rightsowner): self
    {
        $this->rightsowner = $rightsowner;

        return $this;
    }

    public function getRoyalty(): ?float
    {
        return $this->royalty;
    }

    public function setRoyalty(float $royalty): self
    {
        $this->royalty = $royalty;

        return $this;
    }

    public function getViewings(): ?int
    {
        return $this->viewings;
    }

    public function setViewings(int $viewings): self
    {
        $this->viewings = $viewings;

        return $this;
    }

    public function incrementViewing(): void
    {
        $this->viewings++;
    }

    public function addRoyalty(float $ammount): void
    {
        $this->royalty += $ammount;
    }
}
