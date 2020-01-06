<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 30/03/17
 * Time: 14:49
 */

namespace Lch\MenuBundle\Form;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
Use Doctrine\Persistence\ObjectManager;
use Lch\MenuBundle\Entity\MenuItem;
use Symfony\Component\Form\DataTransformerInterface;

class JsonToMenuTreeTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var array
     */
    private $allNodes;

    /**
     * JsonToMenuTreeTransformer constructor.
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param mixed $menuItems
     * @return Collection|null
     */
    public function transform($menuItems) {
        if(null === $menuItems) {
            return null;
        }
        return $menuItems;
    }

    /**
     * Used on form validation, to transform menu nodes in real MenuItem
     * @param mixed $jsonMenuNodes
     * @return null|Collection
     * @throws \Exception
     */
    public function reverseTransform($jsonMenuNodes)
    {
        // TODO add exception
        if ($jsonMenuNodes instanceof Collection && $jsonMenuNodes->count() === 0) {
            return new ArrayCollection();
        }
        $this->allNodes = [];

        // TODO handle no node
        $firstNode = json_decode($jsonMenuNodes->first())[0];

        // Only handle the first collection item which contain all tree
        $menuItemsArray = $this->recursiveNodeHandling(json_decode($jsonMenuNodes->first()));

        // Remove delta which is not present anymore


        return new ArrayCollection($menuItemsArray);
    }

    /**
     * @param array $nodes
     * @param MenuItem $parentMenuItem
     * @return array
     */
    private function recursiveNodeHandling(array $nodes, MenuItem $parentMenuItem = null) {
        $menuItemsArray = [];

        $position = 0;
        // Loop on each given nodes
        foreach($nodes as $node) {

            // Handle direct child
            if(isset($node->persist_id)) {
                $currentMenuItem = $this->manager->getRepository('LchMenuBundle:MenuItem')->find($node->persist_id);
            }
            else {
                $currentMenuItem = new MenuItem();
            }

            $currentMenuItem->setTitle($node->name);
            $currentMenuItem->setTarget($node->url);
            $currentMenuItem->setPosition($position);

            // Set parent
            if(null !== $parentMenuItem) {
                $currentMenuItem->setParent($parentMenuItem);
            }

            $menuItemsArray[] = $currentMenuItem;
            $this->allNodes[] = $currentMenuItem;

            // Handle subchildren recursively
            if(isset($node->children)) {
                $childrenMenuItemsArray = $this->recursiveNodeHandling($node->children, $currentMenuItem);
//                $menuItemsArray = array_merge($menuItemsArray, $childrenMenuItemsArray);
                // Add children
                $currentMenuItem->setChildren(new ArrayCollection($childrenMenuItemsArray));
            }
            $position++;
        }
        return $menuItemsArray;
    }

    /**
     * Transform hierarchical list to flat array for easing comparison
     * @param $items
     * @return array
     */
    private function hierarchyToFlatArray($items) {
        $flatArray = [];
        foreach($items as $item) {
            $flatArray[] = $item->getId();
            // TODO generalize
            if($items->getChildren()->count() > 0) {
                $flatArray = array_merge($flatArray, $this->hierarchyToFlatArray($items->getChildren()));
            }
        }
        return $flatArray;
    }
}