<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 30/03/17
 * Time: 18:04
 */

namespace Lch\MenuBundle\Twig\Extension;


use Doctrine\Common\Collections\Collection;
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
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getMenuItemsJson', [$this, 'getMenuItemsJson' ], [
                'needs_environment' => false
            ]),
            new \Twig_SimpleFunction('getMenuItemJson', [$this, 'getMenuItemJson' ], [
                'needs_environment' => false
            ]),
        ];
    }

    /**
     * Create JSON compatible data for menu display
     * @param Collection $menuItems
     * @return string
     */
    public function getMenuItemsJson(Collection $menuItems) {

        $this->alreadySetIds = [];
        $this->position = 0;
        return json_encode($this->recursiveMenuItemHandling($menuItems));
    }

    /**
     * Recursive method to handle tree nested menus
     * @param Collection $menuItems
     * @return array
     */
    private function recursiveMenuItemHandling(Collection $menuItems) {
        $data = [];
        foreach($menuItems as $menuItem) {
            // This is necessary to avoid to loop on children only when already included as previous parent children
            if(!in_array($menuItem->getId(), $this->alreadySetIds)) {
                $this->alreadySetIds[] = $menuItem->getId();
                $itemNode = [];
                $itemNode['title'] = $menuItem->getTitle();
                $itemNode['url'] = $menuItem->getTarget();
                $itemNode['position'] = $this->position;
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
     * @param MenuItem $menuItem
     * @param int $position
     * @return string
     */
    public function getMenuItemJson(MenuItem $menuItem, int $position) {
        $data = [
            'title' => $menuItem->getTitle(),
            'url' => $menuItem->getTarget(),
            'position' => $position
        ];

        // Handle direct children only (reverse transform will handle the rest
        if($menuItem->getChildren()->count() > 0) {
            foreach($menuItem->getChildren() as $childMenuItem) {
                $data['children'][] = ++$position;
//                $data['children'][] = [
//                    'title' => $chilMenuItem->getTitle(),
//                    'url' => $chilMenuItem->getTarget(),
//                ];
            }
        }
        return json_encode($data);
    }
}