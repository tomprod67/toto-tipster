<?php

namespace App\Entity;

use App\Repository\EuromillionsTiragesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EuroMillionsTiragesRepository::class)
 */
class EuromillionsTirages
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
    private $numC1;

    /**
     * @ORM\Column(type="integer")
     */
    private $numC2;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="string")
     */
    private $month;

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



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNum1()
    {
        return $this->num1;
    }

    /**
     * @param mixed $num1
     */
    public function setNum1($num1): void
    {
        $this->num1 = $num1;
    }

    /**
     * @return mixed
     */
    public function getNum2()
    {
        return $this->num2;
    }

    /**
     * @param mixed $num2
     */
    public function setNum2($num2): void
    {
        $this->num2 = $num2;
    }

    /**
     * @return mixed
     */
    public function getNum3()
    {
        return $this->num3;
    }

    /**
     * @param mixed $num3
     */
    public function setNum3($num3): void
    {
        $this->num3 = $num3;
    }

    /**
     * @return mixed
     */
    public function getNum4()
    {
        return $this->num4;
    }

    /**
     * @param mixed $num4
     */
    public function setNum4($num4): void
    {
        $this->num4 = $num4;
    }

    /**
     * @return mixed
     */
    public function getNum5()
    {
        return $this->num5;
    }

    /**
     * @param mixed $num5
     */
    public function setNum5($num5): void
    {
        $this->num5 = $num5;
    }

    /**
     * @return mixed
     */
    public function getNumC1()
    {
        return $this->numC1;
    }

    /**
     * @param mixed $numC1
     */
    public function setNumC1($numC1): void
    {
        $this->numC1 = $numC1;
    }

    /**
     * @return mixed
     */
    public function getNumC2()
    {
        return $this->numC2;
    }

    /**
     * @param mixed $numC2
     */
    public function setNumC2($numC2): void
    {
        $this->numC2 = $numC2;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

}