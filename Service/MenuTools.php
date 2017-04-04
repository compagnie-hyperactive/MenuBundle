<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 31/03/17
 * Time: 16:54
 */

namespace Lch\MenuBundle\Service;


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class MenuTools
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $manager) {
        $this->entityManager = $manager;
    }

    public function handleMenus($object, Request $request) {
//        $menus = $this->entityManager->getRepository('LchMenuBundle:MenuItem')
        $test = 3;
    }
}