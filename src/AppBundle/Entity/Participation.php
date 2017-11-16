<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParticipationRepository")
 * @ORM\Table(name="participation")
 */
class Participation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ride", inversedBy="participations")
     * @ORM\JoinColumn(name="ride_id", referencedColumnName="id")
     */
    protected $ride;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="participations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateTime;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $goingYes = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $goingMaybe = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $goingNo = true;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $blafeld;

    public function __construct()
    {
        $this->dateTime = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime($dateTime): Participation
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getGoingYes(): bool
    {
        return $this->goingYes;
    }

    public function setGoingYes(bool $goingYes): Participation
    {
        $this->goingYes = $goingYes;

        return $this;
    }

    public function getGoingMaybe(): bool
    {
        return $this->goingMaybe;
    }

    public function setGoingMaybe(bool $goingMaybe): Participation
    {
        $this->goingMaybe = $goingMaybe;

        return $this;
    }

    public function getGoingNo(): bool
    {
        return $this->goingNo;
    }

    public function setGoingNo(bool $goingNo): Participation
    {
        $this->goingNo = $goingNo;

        return $this;
    }

    public function getRide(): ?Ride
    {
        return $this->ride;
    }

    public function setRide(Ride $ride = null): Participation
    {
        $this->ride = $ride;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user = null): Participation
    {
        $this->user = $user;

        return $this;
    }
}
