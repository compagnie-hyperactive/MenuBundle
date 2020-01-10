<?php

namespace Lch\MenuBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Lch\TranslateBundle\Model\Behavior\Translatable;
use Lch\MenuBundle\Validator\Constraints as LchAssert;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="Lch\MenuBundle\Repository\MenuRepository")
 * @LchAssert\ValidMenu
 */
class Menu
{
    use Translatable;

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
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private $title;
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $menuItems;

    /**
     * @return string
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     * @param string $menuItems
     * @return Menu
     */
    public function setMenuItems(string $menuItems): Menu
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

