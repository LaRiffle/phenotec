<?php
// src/PNT/UserBundle/Controller/SecurityController.php;

namespace PNT\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use PNT\UserBundle\Entity\User;

class SecurityController extends Controller
{
  /*
    Controller to deal with all the users:
    - Admin (on init)
    - Record store owners aka User
  */
  public function createAdminAction($key){
    /* Create the first account */
    if($key == $this->getParameter('admin_init')){
      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('PNTUserBundle:User');

      // Test si déjà fait :
      $user = $repository->findOneBy(array('username' => $this->getParameter('admin_username')));
      if($user != null){
        return new Response(
              '<html><body>Nothing to do.</body></html>'
          );
      }

      // On crée l'utilisateur
      $user = new User;

      // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
      $user->setUsername($this->getParameter('admin_username'));
      $user->setPassword($this->getParameter('admin_password'));
      $user->setFirstname($this->getParameter('admin_username'));
      $user->setSurname($this->getParameter('admin_username'));

      // On ne se sert pas du sel pour l'instant
      $user->setSalt('');
      $user->setRoles(array('ROLE_ADMIN'));

      // On le persiste
      $em->persist($user);
      $em->flush();

      return new Response(
            '<html><body>Admin created</body></html>'
        );
    } else {
      return new Response(
            '<html><body>Access denied</body></html>'
        );
    }
  }
  public function loginAction(Request $request)
  {
    // Si le visiteur est déjà identifié, on le redirige vers l'accueil
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
      return $this->redirectToRoute('pnt_site_home');
    }

    // Le service authentication_utils permet de récupérer le nom d'utilisateur
    // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
    // (mauvais mot de passe par exemple)
    $authenticationUtils = $this->get('security.authentication_utils');

    return $this->render('PNTUserBundle:Security:login.html.twig', array(
      'last_username' => $authenticationUtils->getLastUsername(),
      'error'         => $authenticationUtils->getLastAuthenticationError(),
    ));
  }
}
