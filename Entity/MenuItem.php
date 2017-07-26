<?php

namespace Lch\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MenuItem
 *
 * @ORM\Table(name="lch_menu_item")
 * @ORM\Entity(repositoryClass="Lch\MenuBundle\Repository\MenuItemRepository")
 */
class MenuItem
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    /**
     * @var string $target
     * @ORM\Column(name="target", type="string", length=512)
     */
    private $target;
    /**
     * @var bool $enabled;
     * @ORM\Column(name="enabled", type="boolean", options={"default": 1})
     */
    private $enabled = 1;
    /**
     * @var int
     * @ORM\Column(name="position", type="integer", options={"default": 0})
     */
    private $position = 0;
    /**
     * @var MenuItem
     * @ORM\ManyToOne(targetEntity="Lch\MenuBundle\Entity\MenuItem", inversedBy="children", cascade={"all"})
     */
    private $parent;
    /**
     * @var ArrayCollection[MenuItem]
     * @ORM\OneToMany(targetEntity="Lch\MenuBundle\Entity\MenuItem", mappedBy="parent", cascade={"all"}, fetch="EAGER", orphanRemoval=true)
     */
    private $children;

    /**
     * MenuItem constructor.
     * @param string $title
     * @param string $target
     */
    public function __construct($title = "", $target = "") {
        $this->title = $title;
        $this->target = $target;
        $this->children = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return MenuItem
     */
    public function setPosition(int $position): MenuItem
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return MenuItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param MenuItem $parent
     * @return MenuItem
     */
    public function setParent(MenuItem $parent): MenuItem
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $children
     * @return MenuItem
     */
    public function setChildren(ArrayCollection $children): MenuItem
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return MenuItem
     */
    public function setEnabled(bool $enabled): MenuItem
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return MenuItem
     */
    public function setTitle(string $title): MenuItem
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     * @return MenuItem
     */
    public function setTarget(string $target): MenuItem
    {
        $this->target = $target;
        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

