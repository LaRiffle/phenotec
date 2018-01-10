<?php

namespace PNT\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public $entityNameSpace = 'PNTSiteBundle:Home';

    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('PNTSiteBundle:Article');
        $articles = $repository->findBy(array('domain'=>'home'), array('order' => 'desc'));
        return $this->render($this->entityNameSpace.':home.html.twig', array(
          'articles' => $articles,
        ));
    }
    public function aboutUsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('PNTSiteBundle:Article');
        $articles = $repository->findBy(array('domain'=>'about-us'), array('order' => 'desc'));
        return $this->render($this->entityNameSpace.':aboutUs.html.twig', array(
          'articles' => $articles,
        ));
    }
    public function servicesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('PNTSiteBundle:Article');
        $articles = $repository->findBy(array('domain'=>'services'), array('order' => 'desc'));
        return $this->render($this->entityNameSpace.':services.html.twig', array(
          'articles' => $articles,
        ));
    }
    public function newsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('PNTSiteBundle:Article');
        $articles = $repository->findBy(array('domain'=>'news'), array('order' => 'desc'));
        return $this->render($this->entityNameSpace.':news.html.twig', array(
          'articles' => $articles,
        ));
    }
    public function partnersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('PNTSiteBundle:Article');
        $articles = $repository->findBy(array('domain'=>'partners'), array('order' => 'desc'));
        return $this->render($this->entityNameSpace.':partners.html.twig', array(
          'articles' => $articles,
        ));
    }
    public function contactAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('PNTSiteBundle:Article');
        $articles = $repository->findBy(array('domain'=>'contact'), array('order' => 'desc'));
        return $this->render($this->entityNameSpace.':contact.html.twig', array(
          'articles' => $articles,
        ));
    }
}
