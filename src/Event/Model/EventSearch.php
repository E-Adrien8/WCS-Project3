<?php

declare(strict_types=1);

namespace App\Event\Model;

use App\Entity\FoodType;
use App\Entity\Zone;
use DateTime;

class EventSearch
{
    private ?DateTime $date;
    private ?string $meal = null;
    private ?FoodType $type = null;
    private ?Zone $zone = null;
    private ?string $restaurant = null;
    private ?bool $attendees = null;

    public function __construct()
    {
        $this->date = new DateTime();
    }

    public function getAttendees(): ?bool
    {
        return $this->attendees;
    }

    public function setAttendees(?bool $attendees): void
    {
        $this->attendees = $attendees;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }


    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }


    public function getMeal(): ?string
    {
        return $this->meal;
    }


    public function setMeal(?string $meal): void
    {
        $this->meal = $meal;
    }


    public function getType(): ?FoodType
    {
        return $this->type;
    }


    public function setType(?FoodType $type): void
    {
        $this->type = $type;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }


    public function setZone(?Zone $zone): void
    {
        $this->zone = $zone;
    }

    public function getRestaurant(): ?string
    {
        return $this->restaurant;
    }

    public function setRestaurant(?string $restaurant): void
    {
        $this->restaurant = $restaurant;
    }
}
