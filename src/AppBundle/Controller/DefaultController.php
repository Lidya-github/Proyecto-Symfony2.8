<?php
// src/AppBundle/Controller/DefaultController.php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use AppBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
/**
    * @Route("/sitios/{nombrePagina}/", defaults={"nombrePagina" = "ayuda"}, requirements={"nombrePagina"="ayuda|privacidad|sobre_nosotros"}, name="pagina",)
    */
    public function paginaAction($nombrePagina)
    {
        return $this->render('sitios/'.$nombrePagina.'.html.twig',[
            'nombre' => $nombrePagina
        ]);
    }

    public function indexAction()
    {
        $this->getRequest()->setLocale('es_ES');
    }

/**
     * @Route("/{ciudad}",  defaults={"ciudad"="%app.ciudad_por_defecto%"},requirements={"ciudad"="^/(?!login)$"}, name="portada")
     */
    public function portadaAction(Request $request,$ciudad)
    {
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('AppBundle:Oferta')->findOfertaDelDia($ciudad);
        

        if (null === $ciudad) {
            return $this->redirectToRoute('portada', array(
            'ciudad' => $this->getParameter('app.ciudad_por_defecto')
            ));
        }

        if (!$oferta) {
            throw $this->createNotFoundException('No se ha encontrado la oferta del día en la ciudad seleccionada');
        }

        return $this->render('portada.html.twig', array(
            'oferta' => $oferta
        ));

        $locale = $request->getLocale();
            
    }

    public function defaultAction()
    {
        $usuario = new Usuario();
        $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
        $password = $encoder->encodePassword(
            'la-contraseña-en-claro',
            $usuario->getSalt()
        );
        $usuario->setPassword($password);
    }


}

class SitioController extends Controller
{
    public function paginaAction($nombrePagina, Request $request)
    {
        return $this->render(sprintf(
        'sitio/%s/%s.html.twig', $request->getLocale(), $nombrePagina
        ));
    }
}