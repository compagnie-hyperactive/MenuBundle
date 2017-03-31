<?php

namespace Lch\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="Lch\MenuBundle\Repository\MenuRepository")
 * @UniqueEntity(fields={"title"})
 * @UniqueEntity(fields={"location"})
 */
class Menu
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
     * @var string
     * @ORM\Column(type="string", length=256, nullable=false, unique=true)
     */
    private $title;
    /**
     * @var string
     * @ORM\Column(type="string", length=256, nullable=true, unique=true)
     */
    private $location;
    /**
     * @var ArrayCollection[MenuItem]
     * @ORM\ManyToMany(targetEntity="Lch\MenuBundle\Entity\MenuItem", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(name="menu_menu_item")
     */
    private $menuItems;


    public function __construct() {
        $this->menuItems = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getMenuItems(): Collection
    {
        return $this->menuItems;
    }

    /**
     * @param ArrayCollection $menuItems
     * @return Menu
     */
    public function setMenuItems(ArrayCollection $menuItems): Menu
    {
        $this->menuItems = $menuItems;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return Menu
     */
    public function setLocation(string $location): Menu
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Menu
     */
    public function setTitle(string $title): Menu
    {
        $this->title = $title;
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

