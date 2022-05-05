<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Oferta;

class Ofertas extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 3;
    }

    public function load(ObjectManager $manager)
    {
        $ciudades = $manager->getRepository('AppBundle:Ciudad')->findAll();

        foreach($ciudades as $ciudad){
            $tiendas = $manager->getRepository('AppBundle:Tienda')->findByCiudad(
                $ciudad->getId()
            );

            for($i = 1; $i < 40; ++$i){
                $entidad = new Oferta();
    
                $entidad->setNombre('Oferta '.$i);
                $entidad->setPrecio(rand(1, 100));
    
                if(1 === $i){
                    $fecha = 'today';
                    $entidad->setRevisada(true);
                }elseif ($i < 10){
                    $fecha = 'now - '.($i - 1).' days';
                    $entidad->setRevisada((mt_rand(1,1000) % 10) < 80);
                }else{
                    $fecha = 'now - '.($i - 10 + 1).' days';
                    $entidad->setRevisada(true);
                }
                
                $fechaPublicacion = new \DateTime($fecha);
                $fechaExpiracion = clone $fechaPublicacion;
                $fechaExpiracion->add(\DateInterval::createFromDateString('24 hours'));
                $entidad->setFechaPublicacion($fechaPublicacion);
                $entidad->setFechaExpiracion($fechaExpiracion);
    
                $entidad->setUmbral(mt_rand(25,100));
    
                $entidad->setDescripcion('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec convallis magna nec quam dictum mattis. Suspendisse et metus justo. In non enim diam. Vestibulum');
                $entidad->setCondiciones('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec convallis magna nec quam dictum mattis. Suspendisse et metus justo. In non enim diam. Vestibulum'); 
                
                $entidad->setRutaFoto('foto'.mt_rand(1, 20).'.jpg');
                
                $entidad->setDescuento($entidad->getPrecio() * (mt_rand(10, 70) / 100));
                
                $entidad->setCompras(0);
                
                $entidad->setRevisada(1);
                $tienda = $tiendas[array_rand($tiendas)];
                $entidad->setTienda($tienda);
                $entidad->setCiudad($ciudad);
    
                $manager->persist($entidad);
            }
        }
        $manager->flush();
    }
}