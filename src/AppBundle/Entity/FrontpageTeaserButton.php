<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Table(name="frontpage_teaser_button")
 * @ORM\Entity
 */
class FrontpageTeaserButton
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FrontpageTeaser", inversedBy="buttons")
     * @ORM\JoinColumn(name="teaser_id", referencedColumnName="id")
     */
    protected $frontpageTeaser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $caption;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $icon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $class;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $position = 0;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFrontpageTeaser(FrontpageTeaser $frontpageTeaser): FrontpageTeaserButton
    {
        $this->frontpageTeaser = $frontpageTeaser;

        return $this;
    }

    public function getFrontpageTeaser(): ?FrontpageTeaser
    {
        return $this->frontpageTeaser;
    }

    public function setCaption(string $caption = null): FrontpageTeaserButton
    {
        $this->caption = $caption;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setIcon(string $icon = null): FrontpageTeaserButton
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setLink(string $link = null): FrontpageTeaserButton
    {
        $this->link = $link;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setClass(string $class = null): FrontpageTeaserButton
    {
        $this->class = $class;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setPosition(int $position): FrontpageTeaserButton
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setCreatedAt(\DateTime $createdAt): FrontpageTeaserButton
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt = null): FrontpageTeaserButton
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function __toString(): string
    {
        return sprintf('%s: %s (%d)', $this->caption, $this->link, $this->id);
    }
}
