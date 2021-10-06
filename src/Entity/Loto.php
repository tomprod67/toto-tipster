<?php

namespace App\Entity;

use App\Repository\LotoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LotoRepository::class)
 */
class Loto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $num1;

    /**
     * @ORM\Column(type="integer")
     */
    private $num2;

    /**
     * @ORM\Column(type="integer")
     */
    private $num3;

    /**
     * @ORM\Column(type="integer")
     */
    private $num4;

    /**
     * @ORM\Column(type="integer")
     */
    private $num5;

    /**
     * @ORM\Column(type="integer")
     */
    private $numC;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum1(): ?int
    {
        return $this->num1;
    }

    public function setNum1(int $num1): self
    {
        $this->num1 = $num1;

        return $this;
    }

    public function getNum2(): ?int
    {
        return $this->num2;
    }

    public function setNum2(int $num2): self
    {
        $this->num2 = $num2;

        return $this;
    }

    public function getNum3(): ?int
    {
        return $this->num3;
    }

    public function setNum3(int $num3): self
    {
        $this->num3 = $num3;

        return $this;
    }

    public function getNum4(): ?int
    {
        return $this->num4;
    }

    public function setNum4(int $num4): self
    {
        $this->num4 = $num4;

        return $this;
    }

    public function getNum5(): ?int
    {
        return $this->num5;
    }

    public function setNum5(int $num5): self
    {
        $this->num5 = $num5;

        return $this;
    }

    public function getNumC(): ?int
    {
        return $this->numC;
    }

    public function setNumC(int $numC): self
    {
        $this->numC = $numC;

        return $this;
    }

}
