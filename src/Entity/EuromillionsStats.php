<?php

namespace App\Entity;

use App\Repository\EuromillionsStatsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EuromillionsStatsRepository::class)
 */
class EuromillionsStats
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $month;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbTirages;

    /**
     * @ORM\Column(type="array",nullable=true)
     */
    private $occurence = [];

    /**
     * @ORM\Column(type="array",nullable=true)
     */
    private $arrayIdTirages = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month): void
    {
        $this->month = $month;
    }



    public function getNbTirages(): ?int
    {
        return $this->nbTirages;
    }

    public function setNbTirages(?int $nbTirages): self
    {
        $this->nbTirages = $nbTirages;

        return $this;
    }

    public function getOccurence(): ?array
    {
        return $this->occurence;
    }

    public function setOccurence(array $occurence): self
    {
        $this->occurence = $occurence;

        return $this;
    }

    /**
     * @return array
     */
    public function getArrayIdTirages(): array
    {
        return $this->arrayIdTirages;
    }

    /**
     * @param array $arrayIdTirages
     */
    public function setArrayIdTirages(array $arrayIdTirages): void
    {
        $this->arrayIdTirages = $arrayIdTirages;
    }


}
