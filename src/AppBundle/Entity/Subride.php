<?php

namespace AppBundle\Entity;

use AppBundle\EntityInterface\AuditableInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubrideRepository")
 * @ORM\Table(name="subride")
 * @JMS\ExclusionPolicy("all")
 */
class Subride implements AuditableInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ride", inversedBy="subrides")
     * @ORM\JoinColumn(name="ride_id", referencedColumnName="id")
     * @JMS\Expose
     */
    protected $ride;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @JMS\Expose
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @JMS\Expose
     */
    protected $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Expose
     */
    protected $dateTime;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @JMS\Expose
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Expose
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @JMS\Expose
     */
    protected $location;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @JMS\Expose
     */
    protected $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @JMS\Expose
     */
    protected $longitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url()
     * @JMS\Expose
     */
    protected $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url()
     * @JMS\Expose
     */
    protected $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url()
     * @JMS\Expose
     */
    protected $url;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __clone()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): Subride
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description): Subride
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("timestamp")
     * @JMS\Type("integer")
     */
    public function getTimestamp(): int
    {
        return $this->dateTime->format('U');
    }

    public function setDateTime(\DateTime $dateTime): Subride
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function setLocation(string $location): Subride
    {
        $this->location = $location;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLatitude(float $latitude): Subride
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLongitude(float $longitude): Subride
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setFacebook(string $facebook = null): Subride
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setTwitter(string $twitter = null): Subride
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setUrl(string $url = null): Subride
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

   public function setRide(Ride $ride = null): Subride
    {
        $this->ride = $ride;

        return $this;
    }

    public function getRide(): Ride
    {
        return $this->ride;
    }

    public function setUser(User $user = null): Subride
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setCreatedAt(\DateTime $createdAt): Subride
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt = null): Subride
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getTime(): \DateTime
    {
        return $this->dateTime;
    }

    /** @deprecated  */
    public function setTime(\DateTime $time): Subride
    {
        $this->dateTime = new \DateTime($this->dateTime->format('Y-m-d') . ' ' . $time->format('H:i:s'));
    }
}
