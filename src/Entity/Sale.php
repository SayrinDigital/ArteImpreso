<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleRepository")
 */
class Sale
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,  unique=true)
     */
    private $orderid;

    /**
     * @ORM\Column(type="json")
     */
    private $resume;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientmail;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $clientphone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderid(): ?int
    {
        return $this->orderid;
    }

    public function setOrderid(int $orderid): self
    {
        $this->orderid = $orderid;

        return $this;
    }

    public function getResume()
    {
        return $this->resume;
    }

    public function setResume($resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getClientname(): ?string
    {
        return $this->clientname;
    }

    public function setClientname(string $clientname): self
    {
        $this->clientname = $clientname;

        return $this;
    }

    public function getClientmail(): ?string
    {
        return $this->clientmail;
    }

    public function setClientmail(string $clientmail): self
    {
        $this->clientmail = $clientmail;

        return $this;
    }

    public function getClientphone(): ?string
    {
        return $this->clientphone;
    }

    public function setClientphone(?string $clientphone): self
    {
        $this->clientphone = $clientphone;

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
}
