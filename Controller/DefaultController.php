<?php

namespace Lch\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LchMenuBundle:Default:index.html.twig');
    }
}
