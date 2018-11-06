<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotifiedSaleRepository")
 */
class NotifiedSale
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $saleid;

    /**
     * @ORM\Column(type="text")
     */
    private $resume;

    /**
     * @ORM\Column(type="integer")
     */
    private $cost;

    /**
     * @ORM\Column(type="datetime")
     */
    private $paymentdate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $c_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $c_address;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSaleid(): ?string
    {
        return $this->saleid;
    }

    public function setSaleid(string $saleid): self
    {
        $this->saleid = $saleid;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getPaymentdate(): ?\DateTimeInterface
    {
        return $this->paymentdate;
    }

    public function setPaymentdate(\DateTimeInterface $paymentdate): self
    {
        $this->paymentdate = $paymentdate;

        return $this;
    }

    public function getCName(): ?string
    {
        return $this->c_name;
    }

    public function setCName(string $c_name): self
    {
        $this->c_name = $c_name;

        return $this;
    }

    public function getCAddress(): ?string
    {
        return $this->c_address;
    }

    public function setCAddress(?string $c_address): self
    {
        $this->c_address = $c_address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
