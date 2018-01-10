<?php

namespace PNT\SiteBundle\Controller;

use PNT\SiteBundle\Entity\Article;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function addAction(Request $request, $domain, $id = 0) {
        $em = $this->getDoctrine()->getManager();
        $oldFileName = null;
        if($id == 0) {
            $article = new Article();
            if( !in_array($domain, ['home', 'about-us', 'services', 'news', 'partners', 'contact'])){
              return new Response(
                    '<html><body>Invalid domain</body></html>'
                );
            }
            $article->setDomain($domain);
            $article->setRightAlign(true);
            $article->setVisible(true);
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
        ->add('visible', CheckboxType::class, array(
          'required' => false
        ))
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
            if($id == 0){
                $article->setOrder($article->getId());
                $em->persist($article);
                $em->flush();
            }
            if($request->query->get('remove_image') != null){
              $article->setImage('');
              $em->persist($article);
              $em->flush();
              return $this->redirect($this->generateUrl('pnt_site_article_add', array(
                'id' => $article->getId(),
                'domain' => $article->getDomain(),
              )));
            } elseif($request->query->get('new_pub') != null){
              return $this->redirect($this->generateUrl('pnt_site_publication_add').'?article='.$article->getId());
            } else {
              $url = 'pnt_site_home';
              switch ($article->getDomain()) {
                case 'about-us':
                  $url = 'pnt_site_about_us';
                  break;
                case 'services':
                  $url = 'pnt_site_services';
                  break;
                case 'news':
                  $url = 'pnt_site_news';
                  break;
                case 'partners':
                  $url = 'pnt_site_partners';
                  break;
                case 'contact':
                  $url = 'pnt_site_contact';
                  break;
              }
              return $this->redirect($this->generateUrl($url));
            }
        }
        return $this->render($this->entityNameSpace.':add.html.twig', array(
            'form' => $form->createView(),
            'articleId' => $id,
            'articleDomain' => $article->getDomain(),
            'img' => $article_img_url,
            'last_url' => $request->headers->get('referer'),
        ));
    }
    public function removeAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository($this->entityNameSpace)->find($id);
        $em->remove($article);
        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }
    public function displayAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository($this->entityNameSpace);
        $article = $repository->find($id);
        $article->setVisible(true);
        $em->persist($article);
        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }
    public function hideAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository($this->entityNameSpace);
        $article = $repository->find($id);
        $article->setVisible(false);
        $em->persist($article);
        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }
    public function swipeAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository($this->entityNameSpace);

      $from_id = $request->request->get('from_id');
      $from_order = $request->request->get('from_order');
      $to_id = $request->request->get('to_id');
      $to_order = $request->request->get('to_order');

      $article_from = $repository->find($from_id);
      $article_from->setOrder($to_order);
      $article_to = $repository->find($to_id);
      $article_to->setOrder($from_order);

      $em->persist($article_from);
      $em->persist($article_to);
      $em->flush();
      return new JsonResponse(['OK' => 'Done.']);
    }
}
