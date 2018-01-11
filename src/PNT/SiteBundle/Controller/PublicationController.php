<?php

namespace PNT\SiteBundle\Controller;

use PNT\SiteBundle\Entity\Publication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PublicationController extends Controller
{
    public $entityNameSpace = 'PNTSiteBundle:Publication';

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository($this->entityNameSpace);
        $publications = $repository->findAll();
        return $this->render($this->entityNameSpace.':index.html.twig', array(
          'publications' => $publications,
        ));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository($this->entityNameSpace);
        $publication = $repository->find($id);
        return $this->render('PNTSiteBundle:Publication:show.html.twig', array(
          'publication' => $publication,
        ));
    }
    public function addAction(Request $request, $id = 0) {
        $em = $this->getDoctrine()->getManager();
        $oldFileName = null;
        if($id == 0) {
            $publication = new Publication();
        } else {
            $repository = $em->getRepository($this->entityNameSpace);
            $publication = $repository->find($id);
            if($publication->getFile() != ''){
              $oldFileName = $publication->getFile();
              $publication->setFile(
                  new File($this->getParameter('img_dir').'/'.$publication->getFile())
              );
            }
        }
        if($oldFileName != null) {
          $publication_file_url = $oldFileName;
        } else {
          $publication_file_url = '';
        }
        $form = $this->get('form.factory')->createBuilder(FormType::class, $publication)
        ->add('name', TextType::class, array('required' => False))
        ->add('file', FileType::class, array('label' => 'Publication', 'required' => False))
        ->add('save',	SubmitType::class)
        ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $publication->getFile();
            if($file != null) {
              // Generate a unique name for the file before saving it
              $fileName = md5(uniqid()).'.'.$file->guessExtension();

              // Move the file to the directory where files are stored
              $file->move(
                  $this->getParameter('img_dir'),
                  $fileName
              );

              // Update the 'file' property to store the file name
              // instead of its contents
              $publication->setFile($fileName);
            } elseif($oldFileName != null) {
              $publication->setFile($oldFileName);
            } else {
              $publication->setFile('');
            }

            // IF no name specified, keep the original file name
            //$slughandler = $this->container->get('tvf_record.slugifyhandler');
            //$slug = $slughandler->slugify($publication->getFileREALNAme());
            //$publication->setName($slug);

            $em = $this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush();
            if($request->query->get('article') != null){
              $article_id = $request->query->get('article');
              // if new publication, add it by default to the article, otherwise it's already done
              if($id == 0){
                $repository = $em->getRepository('PNTSiteBundle:Article');
                $article = $repository->find($article_id);
                $article->addPublication($publication);
                $em->persist($article);
                $em->flush();
              }
              return $this->redirect(
                $this->generateUrl(
                  'pnt_site_article_add',
                  array('id' => $article_id)
                ).'#publication_selected'
              );
            } else {
              return $this->redirect($this->generateUrl('pnt_site_publication'));
            }
        }
        if($request->query->get('article') != null){
          $back_to_article = $request->query->get('article');
          $last_url = $this->generateUrl('pnt_site_article_add', array('id' => $back_to_article));
        } else {
          $back_to_article = false;
          $last_url = $request->headers->get('referer');
        }
        return $this->render($this->entityNameSpace.':add.html.twig', array(
            'form' => $form->createView(),
            'publicationId' => $id,
            'file' => $publication_file_url,
            'last_url' => $last_url,
            'back_to_article' => $back_to_article,
        ));
    }
    public function removeAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository($this->entityNameSpace)->find($id);
        $em->remove($publication);
        $em->flush();
        if($request->query->get('article') != null){
          $article_id = $request->query->get('article');
          return $this->redirect(
            $this->generateUrl(
              'pnt_site_article_add',
              array('id' => $article_id)
            ).'#publication_selected'
          );
        } else {
          return $this->redirect($this->generateUrl('pnt_site_publication'));
        }
    }
}
