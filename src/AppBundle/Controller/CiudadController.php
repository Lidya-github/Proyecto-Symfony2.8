<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class CiudadController extends Controller
{
    /**
    * @Route("/ciudad/cambiar-a-{ciudad}", requirements={ "ciudad" = ".+" }, name="ciudad_cambiar")
    */
    public function cambiarAction($ciudad)
    {
        return $this->redirectToRoute('portada', array('ciudad' => $ciudad));
    }

    public function listaCiudadesAction($ciudad)
    {
        $em = $this->getDoctrine()->getManager();
        $ciudades = $em->getRepository('AppBundle:Ciudad')->findAll();

        return $this->render('ciudad/_lista_ciudades.html.twig', array(
            'ciudades' => $ciudades,
            'ciudadActual' => $ciudad
        ));
    }

    /**
    * @Route("/{ciudad}/recientes",name="ciudad_recientes")
    */
    public function recientesAction($ciudad)
    {
        $em = $this->getDoctrine()->getManager();
        $ciudad = $em->getRepository('AppBundle:Ciudad')->findOneBySlug($ciudad);
        $cercanas = $em->getRepository('AppBundle:Ciudad')->findCercanas($ciudad->getId());
        $ofertas = $em->getRepository('AppBundle:Oferta')->findRecientes($ciudad->getId());

        if (!$ciudad) {
            throw $this->createNotFoundException('No existe la ciudad');
        }    
        return $this->render('ciudad/recientes.html.twig', array(
            'ciudad' => $ciudad,
            'cercanas' => $cercanas,
            'ofertas' => $ofertas
        ));

    }

}