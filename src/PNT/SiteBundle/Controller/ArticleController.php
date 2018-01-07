<?php

namespace PNT\SiteBundle\Controller;

use PNT\SiteBundle\Entity\Article;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleController extends Controller
{
    public $entityNameSpace = 'PNTSiteBundle:Article';

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository($this->entityNameSpace);
        $articles = $repository->findAll();
        return $this->render($this->entityNameSpace.':index.html.twig', array(
          'articles' => $articles,
        ));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository($this->entityNameSpace);
        $article = $repository->find($id);
        return $this->render('PNTSiteBundle:Article:show.html.twig', array(
          'article' => $article,
        ));
    }

    public function addAction(Request $request, $id = 0) {
        $em = $this->getDoctrine()->getManager();
        $oldFileName = null;
        if($id == 0) {
            $article = new Article();
        } else {
            $repository = $em->getRepository($this->entityNameSpace);
            $article = $repository->find($id);
            if($article->getImage() != ''){
              $oldFileName = $article->getImage();
              $article->setImage(
                  new File($this->getParameter('img_dir').'/'.$article->getImage())
              );
            }
        }
        if($oldFileName != null) {
          $article_img_url = $oldFileName;
        } else {
          $article_img_url = '';
        }
        $form = $this->get('form.factory')->createBuilder(FormType::class, $article)
        ->add('title', TextType::class)
        ->add('domain', ChoiceType::class, array(
            'choices'  => array(
                'Home' => 'home',
                'About us' => 'about-us',
                'Services' => 'services',
                'News' => 'news',
                'Partners' => 'partners',
                'Contact' => 'contact'
            ),
        ))
        ->add('text', TextareaType::class)
        ->add('more_text', TextareaType::class, array('required' => False))
        ->add('image', FileType::class, array('label' => 'Image', 'required' => False))
        ->add('right_align', CheckboxType::class, array(
          'required' => false
        ))
        ->add('publications', EntityType::class, array(
                'class'        => 'PNTSiteBundle:Publication',
                'choice_label' => 'name',
                'multiple'     => true,
                'expanded'     => true,
                'required'     => false))
        ->add('save',	SubmitType::class)
        ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $article->getImage();
            if($file != null) {
              // Generate a unique name for the file before saving it
              $fileName = md5(uniqid()).'.'.$file->guessExtension();

              // Move the file to the directory where images are stored
              $file->move(
                  $this->getParameter('img_dir'),
                  $fileName
              );
              // Check orientation
              $path = $this->getParameter('img_dir').'/'.$fileName;
              $imagehandler = $this->container->get('pnt_site.imagehandler');
              $imagehandler->image_fix_orientation($path);

              // Update the 'image' property to store the file name
              // instead of its contents
              $article->setImage($fileName);
            } elseif($oldFileName != null) {
              $article->setImage($oldFileName);
            } else {
              $article->setImage('');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl('pnt_site_article'));
        }
        return $this->render($this->entityNameSpace.':add.html.twig', array(
            'form' => $form->createView(),
            'articleId' => $id,
            'img' => $article_img_url,
        ));
    }
    public function removeAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository($this->entityNameSpace)->find($id);
        $em->remove($article);
        $em->flush();
        return $this->redirect($this->generateUrl('pnt_site_article'));
    }
}
