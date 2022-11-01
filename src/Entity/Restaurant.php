<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $chefName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $address;

    #[ORM\Column(type: 'string', length: 255)]
    private string $zipCode;

    #[ORM\Column(type: 'string', length: 255)]
    private string $city;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $menuText;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $mainPicture;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $tripadvisorLink;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $websiteLink;

    #[ORM\ManyToOne(targetEntity: FoodType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private FoodType $foodType;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $facebookLink;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $instagramLink;

    #[ORM\ManyToOne(targetEntity: Restorer::class, inversedBy: 'restaurants')]
    #[ORM\JoinColumn(nullable: false)]
    private Restorer $restorer;

    #[ORM\ManyToOne(targetEntity: Zone::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Zone $zone;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'string', length: 255)]
    private string $siret;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $billingAddress;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $vat;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $menuPdf;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $averagePrice;

    #[ORM\Column(type: 'string', length: 255)]
    private string $phoneNumber;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Comment::class, cascade: ['all'])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Picture::class, cascade: ['all'])]
    private Collection $pictures;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Event::class, cascade: ['all'])]
    private Collection $events;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $latitude;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $longitude;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function getChefName(): ?string
    {
        return $this->chefName;
    }

    public function setChefName(?string $chefName): self
    {
        $this->chefName = $chefName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getMenuText(): ?string
    {
        return $this->menuText;
    }

    public function setMenuText(?string $menuText): self
    {
        $this->menuText = $menuText;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(?string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getTripadvisorLink(): ?string
    {
        return $this->tripadvisorLink;
    }

    public function setTripadvisorLink(?string $tripadvisorLink): self
    {
        $this->tripadvisorLink = $tripadvisorLink;

        return $this;
    }

    public function getWebsiteLink(): ?string
    {
        return $this->websiteLink;
    }

    public function setWebsiteLink(?string $websiteLink): self
    {
        $this->websiteLink = $websiteLink;

        return $this;
    }

    public function getFoodType(): ?FoodType
    {
        return $this->foodType;
    }

    public function setFoodType(?FoodType $foodType): self
    {
        $this->foodType = $foodType;

        return $this;
    }

    public function getFacebookLink(): ?string
    {
        return $this->facebookLink;
    }

    public function setFacebookLink(?string $facebookLink): self
    {
        $this->facebookLink = $facebookLink;

        return $this;
    }

    public function getInstagramLink(): ?string
    {
        return $this->instagramLink;
    }

    public function setInstagramLink(?string $instagramLink): self
    {
        $this->instagramLink = $instagramLink;

        return $this;
    }

    public function getRestorer(): ?Restorer
    {
        return $this->restorer;
    }

    public function setRestorer(?Restorer $restorer): self
    {
        $this->restorer = $restorer;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?string $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getVat(): ?string
    {
        return $this->vat;
    }

    public function setVat(?string $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getMenuPdf(): ?string
    {
        return $this->menuPdf;
    }

    public function setMenuPdf(?string $menuPdf): self
    {
        $this->menuPdf = $menuPdf;

        return $this;
    }

    public function getAveragePrice(): ?int
    {
        return $this->averagePrice;
    }

    public function setAveragePrice(?int $averagePrice): self
    {
        $this->averagePrice = $averagePrice;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRestaurant($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRestaurant() === $this) {
                $comment->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvent(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setRestaurant($this);
        }

        return $this;
    }

    public function removeTheme(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getRestaurant() === $this) {
                $event->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
