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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $car_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pick_up_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $drop_off_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pick_up_location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $drop_off_location;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
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

    public function setCustomerId(?int $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getCarId(): ?int
    {
        return $this->car_id;
    }

    public function setCarId(?int $car_id): self
    {
        $this->car_id = $car_id;

        return $this;
    }

    public function getPickUpDate(): ?string
    {
        return $this->pick_up_date;
    }

    public function setPickUpDate(?string $pick_up_date): self
    {
        $this->pick_up_date = $pick_up_date;

        return $this;
    }

    public function getDropOffDate(): ?string
    {
        return $this->drop_off_date;
    }

    public function setDropOffDate(?string $drop_off_date): self
    {
        $this->drop_off_date = $drop_off_date;

        return $this;
    }

    public function getPickUpLocation(): ?string
    {
        return $this->pick_up_location;
    }

    public function setPickUpLocation(?string $pick_up_location): self
    {
        $this->pick_up_location = $pick_up_location;

        return $this;
    }

    public function getDropOffLocation(): ?string
    {
        return $this->drop_off_location;
    }

    public function setDropOffLocation(?string $drop_off_location): self
    {
        $this->drop_off_location = $drop_off_location;

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
