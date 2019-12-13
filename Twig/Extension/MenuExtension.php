<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 30/03/17
 * Time: 18:04
 */

namespace Lch\MenuBundle\Twig\Extension;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Lch\MenuBundle\Entity\Menu;
use Lch\MenuBundle\Entity\MenuItem;

class MenuExtension extends \Twig_Extension
{

    /**
     * @var array
     */
    private $alreadySetIds;

    /**
     * @var int
     */
    private $position;

    /**
     * @var object
     */
    private $currentOwner;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var array
     */
    private $locations;

    public function __construct(EntityManager $manager, array $locations)
    {
        $this->manager   = $manager;
        $this->locations = $locations;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getMenuItemsJson', [$this, 'getMenuItemsJson'], [
                'needs_environment' => false
            ]),
            new \Twig_SimpleFunction('get_menu_items', [$this, 'getMenuItems'], [
                'needs_environment' => false
            ])
        ];
    }

    /**
     * Create JSON compatible data for menu display
     *
     * @param Collection $menuItems
     *
     * @return string
     */
    public function getMenuItemsJson(Collection $menuItems, $currentOwner)
    {

        $this->alreadySetIds = [];
        $this->position      = 0;
        $this->currentOwner  = $currentOwner;

        return json_encode($this->recursiveMenuItemHandling($menuItems));
    }

    /**
     * Recursive method to handle tree nested menus
     *
     * @param Collection $menuItems
     *
     * @return array
     */
    private function recursiveMenuItemHandling(Collection $menuItems)
    {
        $data = [];
        foreach ($menuItems as $menuItem) {
            // This is necessary to avoid to loop on children only when already included as previous parent children
            if (! in_array($menuItem->getId(), $this->alreadySetIds)) {
                $this->alreadySetIds[]  = $menuItem->getId();
                $itemNode               = [];
                $itemNode['name']       = $menuItem->getTitle();
                $itemNode['url']        = $menuItem->getTarget();
                $itemNode['id']         = $this->position;
                $itemNode['persist_id'] = $menuItem->getId();
                if (null === $menuItem->getParent()) {
                    $itemNode['owner_type'] = get_class($this->currentOwner);
                    $itemNode['owner_id']   = $this->currentOwner->getId();
                }
                $this->position++;
                if ($menuItem->getChildren()->count() > 0) {
                    $itemNode['children'] = $this->recursiveMenuItemHandling($menuItem->getChildren());
                }
                $data[] = $itemNode;
            }
        }

        return $data;
    }


    /**
     * @param string $location
     * @param string|null $locale
     *
     * @return array
     */
    public function getMenuItems(string $location, string $locale = null)
    {
        $params = ['location' => $location];
        if($locale !== null) {
            $params['language'] = $locale;
        }

        $menu   = $this->manager->getRepository('LchMenuBundle:Menu')->findOneBy($params);

        if ($menu instanceof Menu) {
            $menuItems = json_decode($menu->getMenuItems());
            if (is_array($menuItems)) {
                return $menuItems;
            }
        }

        return [];
    }
}