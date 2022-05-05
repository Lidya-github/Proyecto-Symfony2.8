<?php
// src/AppBundle/Controller/OfertaController.php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


    class OfertaController extends Controller
    {
        /**
        * @Route("/{ciudad}/ofertas/{slug}", name="oferta")
        */
        public function ofertaAction($ciudad, $slug)
        {
            $em = $this->getDoctrine()->getManager();
            $oferta = $em->getRepository('AppBundle:Oferta')->findOferta($ciudad, $slug);
            $relacionadas = $em->getRepository('AppBundle:Oferta')->findRelacionadas($ciudad);

            if (!$oferta) {
                throw $this->createNotFoundException('No existe la oferta');
            }

            return $this->render('oferta/detalle.html.twig', array(
                'oferta' => $oferta,
                'relacionadas' => $relacionadas
            ));
        }
    }

