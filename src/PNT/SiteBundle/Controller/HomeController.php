<?php

namespace PNT\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public $entityNameSpace = 'PNTSiteBundle:Home';

    public function homeAction()
    {
        return $this->render($this->entityNameSpace.':home.html.twig');
    }
}
