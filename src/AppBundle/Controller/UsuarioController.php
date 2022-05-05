<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Usuario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

    /**
    * @Route("/usuario")
    */
    class UsuarioController extends Controller
    {
        /**
        * @Route("/compras/", name="usuario_compras")
        */
        public function comprasAction()
        {
            $usuario = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $compras = $em->getRepository('AppBundle:Usuario')->findTodasLasCompras($usuario->getId());
            return $this->render('usuario/compras.html.twig', array(
                'compras' => $compras
            ));
        }

        /**
        * @Route("/login/", name="usuario_login")
        */
        public function loginAction()
        {
            $authUtils = $this->get('security.authentication_utils');
            return $this->render('usuario/login.html.twig', array(
                'last_username' => $authUtils->getLastUsername(),
                'error' => $authUtils->getLastAuthenticationError(),
            ));
        }

        /**
        * @Route("/registro", name="usuario_registro")
        */
        public function registroAction(Request $request)
        {
            $usuario = new Usuario();
            $formulario = $this->createForm('AppBundle\Form\UsuarioType', $usuario,array(
                'accion' => 'crear_usuario',
                'validation_groups' => array('default', 'registro'),
            ));

            $formulario->handleRequest($request);

            if ($formulario->isValid()) {
                $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
                $passwordCodificado = $encoder->encodePassword($usuario->getPassword(), null);
                $usuario->setPassword($passwordCodificado);
                $em = $this->getDoctrine()->getManager();
                $em->persist($usuario);
                $em->flush();
                $token = new UsernamePasswordToken(
                    $usuario,
                    $usuario->getPassword(),
                    'frontend',
                    $usuario->getRoles()
                );
                $this->container->get('security.token_storage')->setToken($token);
                $this->addFlash('info', '¡Enhorabuena! Te has registrado correctamente en Cupon');
                return $this->redirectToRoute('portada', array('ciudad' => $usuario->getCiudad()->getSlug())); 
            }

            return $this->render('usuario/registro.html.twig', array(
                'formulario' => $formulario->createView()
            ));
        }


        public function cajaLoginAction()
        {
            $authUtils = $this->get('security.authentication_utils');
            return $this->render('usuario/_caja_login.html.twig', array(
                'last_username' => $authUtils->getLastUsername(),
                'error' => $authUtils->getLastAuthenticationError(),
            ));
        }

        /**
        * @Route("/login_check", name="usuario_login_check")
        */
        public function loginCheckAction()
        {
        // el "login check" lo hace Symfony automáticamente, por lo que
        // no hay que añadir ningún código en este método
        }

        /**
        * @Route("/logout", name="usuario_logout")
        */
        public function logoutAction()
        {
        // el logout lo hace Symfony automáticamente, por lo que
        // no hay que añadir ningún código en este método
        }

        /**
        * @Route("/perfil", name="usuario_perfil")
        */
        public function perfilAction(Request $request)
        {          
            $usuario = $this->getUser();
            $formulario = $this->createForm('AppBundle\Form\UsuarioType', $usuario);
            $formulario->handleRequest($request);
            if ($formulario->isValid()) {
                if (null !== $usuario->getPasswordEnClaro()) {
                    $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
                    $passwordCodificado = $encoder->encodePassword(
                    $usuario->getPasswordEnClaro(),null);
                    $usuario->setPassword($passwordCodificado);
                }
                // ...
            }


            return $this->render('usuario/perfil.html.twig', array(
                'usuario' => $usuario,
                'formulario' => $formulario->createView()
            ));

        }

        
}