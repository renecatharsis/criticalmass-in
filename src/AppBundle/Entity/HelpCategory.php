<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="help_category")
 * @ORM\Entity()
 */
class HelpCategory
{
    const SIDE_LEFT = 'left';
    const SIDE_RIGHT = 'right';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=16)
     */
    protected $language;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $intro;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $side;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $position = 0;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\HelpItem", mappedBy="category")
     */
    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): HelpCategory
    {
        $this->language = $language;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): HelpCategory
    {
        $this->title = $title;

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(string $intro): HelpCategory
    {
        $this->intro = $intro;

        return $this;
    }

    public function getSide(): ?string
    {
        return $this->side;
    }

    public function setSide(string $side): HelpCategory
    {
        $this->side = $side;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): HelpCategory
    {
        $this->position = $position;

        return $this;
    }

    public function addItem(HelpItem $item): HelpCategory
    {
        $this->items->add($item);

        return $this;
    }

    public function setItems(Collection $items): HelpCategory
    {
        $this->items = $items;

        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function removeItem(HelpItem $item): HelpCategory
    {
        $this->items->removeElement($item);

        return $this;
    }
    
    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->title, $this->language);
    }
}
