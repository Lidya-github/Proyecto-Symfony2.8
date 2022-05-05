<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use AppBundle\Form\OfertaType;
use AppBundle\Entity\Oferta;

/**
* @Route("/")
*/
    class ExtranetController extends Controller
    {

        /**
        * @Route("/", name="extranet_portada")
        */
        public function portadaAction() {
            $em = $this->getDoctrine()->getManager();

            $tienda = $this->get('security.token_storage')->getToken()->getUser();
            $ofertas = $em->getRepository('AppBundle:Tienda')->findOfertasRecientes($tienda->getId(), 50);

            return $this->render('extranet/portada.html.twig', array(
                'ofertas' => $ofertas,
            ));
         }

        /**
        * @Route("/oferta/ventas/{id}", name="extranet_oferta_ventas")
        */
        public function ofertaVentasAction() {
            
        }

        /**
        * @Route("/oferta/nueva", name="extranet_oferta_nueva")
        */
        public function ofertaNuevaAction(Request $request) {
            $oferta = new Oferta();
            $formulario = $this->createForm('AppBundle\Form\OfertaType', $oferta,array('mostrar_condiciones' => true));
            $formulario->handleRequest($request);

            if ($formulario->isValid()) {
                $tienda = $this->getUser();
                $oferta->setCompras(0);
                $oferta->setTienda($tienda);
                $oferta->setCiudad($tienda->getCiudad());
                $fechaPublicacion=new \DateTime('now');
                $oferta->setFechaPublicacion($fechaPublicacion);
                $oferta->subirFoto(
                    $this->container->getParameter('cupon.directorio.imagenes')
                );                    

                $em = $this->getDoctrine()->getManager();
                $em->persist($oferta);
                $em->flush();
                return $this->redirectToRoute('extranet_portada');
            }

            return $this->render('extranet/oferta.html.twig', array(
                'formulario' => $formulario->createView(),
                'accion' => 'crear'
            ));
        }

        /**
        * @Route("/oferta/editar/{id}", name="extranet_oferta_editar")
        */
        public function ofertaEditarAction(Request $request,$id) 
        { 
            
            $em = $this->getDoctrine()->getManager();
            $oferta = $em->getRepository('AppBundle:Oferta')->find($id);
    
            if(!$oferta){
                throw $this->createNotFoundException('La oferta no existe');
            }
    
            if(false === $this->isGranted('ROLE_TIENDA', $oferta)){
                throw new AccessDeniedException();
            }
    
            if($oferta->getRevisada()){
                $this->addFlash('error', 'La oferta no se puede modificar porque ya ha sido revisada');
                return $this->redirect($this->generateUrl('extranet_portada'));
            }
    
            $formulario = $this->createForm('AppBundle\Form\OfertaType', $oferta);
    
            $rutaFotoOriginal = $formulario->getData()->getRutaFoto();
    
            $formulario->handleRequest($request);
    
            if($formulario->isValid()){
                if (null == $oferta->getFoto()) {
                    $oferta->setRutaFoto($rutaFotoOriginal);
                } else {
                    $directorioFotos = $this->container->getParameter(
                    'cupon.directorio.imagenes'
                    );
                    $oferta->subirFoto($directorioFotos);
                    unlink($directorioFotos.$rutaFotoOriginal);
                }
    
                $em = $this->getDoctrine()->getManager();
                $em->persist($oferta);
                $em->flush();
    
                return $this->redirect(
                    $this->generateUrl('extranet_portada')
                );
            }
    
            return $this->render('extranet/oferta.html.twig',
                array(
                    'oferta' => $oferta,
                    'formulario' => $formulario->createView(),
                    'accion' => 'editar'
                )
            );
        }
        
        /**
        * @Route("/perfil", name="extranet_perfil")
        */
        public function perfilAction(Request $request) 
        {
            $tienda = $this->getUser();
            $formulario = $this->createForm('AppBundle\Form\TiendaType', $tienda);
            $formulario->handleRequest($request);

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($tienda);
                $em->flush();
                $this->addFlash('info', 'Los datos de tu perfil se han actualizado correctamente');

                return $this->redirectToRoute('extranet_portada');
            }

            return $this->render('extranet/perfil.html.twig', array(
                'tienda' => $tienda,
                'formulario' => $formulario->createView()
            ));
        }
        
        /**
        * @Route("/login", name="extranet_login")
        */
        public function loginAction()
        {
            $authUtils = $this->get('security.authentication_utils');
            return $this->render('extranet/login.html.twig', array(
                'last_username' => $authUtils->getLastUsername(),
                'error' => $authUtils->getLastAuthenticationError(),
            ));
        }
                
        /**
        * @Route("/login_check", name="extranet_login_check")
        */
        public function loginCheckAction()
        {
        // el "login check" lo hace Symfony automáticamente, por lo que
        // no hay que añadir ningún código en este método
        }
        /**
        * @Route("/logout", name="extranet_logout")
        */
        public function logoutAction()
        {
        // el logout lo hace Symfony automáticamente, por lo que
        // no hay que añadir ningún código en este método
        }

    }