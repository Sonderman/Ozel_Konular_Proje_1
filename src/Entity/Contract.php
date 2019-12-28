<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContractRepository")
 */
class Contract
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $customer_id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $pickup_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dropoff_date;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $pickup_location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dropoff_location;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Car", mappedBy="contract", cascade={"persist", "remove"})
     */
    private $car;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="contracts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    public function setCustomerId(int $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getPickupDate(): ?\DateTimeInterface
    {
        return $this->pickup_date;
    }

    public function setPickupDate(\DateTimeInterface $pickup_date): self
    {
        $this->pickup_date = $pickup_date;

        return $this;
    }

    public function getDropoffDate(): ?\DateTimeInterface
    {
        return $this->dropoff_date;
    }

    public function setDropoffDate(\DateTimeInterface $dropoff_date): self
    {
        $this->dropoff_date = $dropoff_date;

        return $this;
    }

    public function getPickupLocation(): ?string
    {
        return $this->pickup_location;
    }

    public function setPickupLocation(string $pickup_location): self
    {
        $this->pickup_location = $pickup_location;

        return $this;
    }

    public function getDropoffLocation(): ?string
    {
        return $this->dropoff_location;
    }

    public function setDropoffLocation(string $dropoff_location): self
    {
        $this->dropoff_location = $dropoff_location;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        // set (or unset) the owning side of the relation if necessary
        $newContract = null === $car ? null : $this;
        if ($car->getContract() !== $newContract) {
            $car->setContract($newContract);
        }

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
