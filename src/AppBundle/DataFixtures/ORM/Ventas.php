<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Venta;

class Ventas extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 5;
    }

    public function load(ObjectManager $manager)
    {
        $ofertas = $manager->GetRepository('AppBundle:Oferta')->FindAll();
        $usuarios = $manager->GetRepository('AppBundle:Usuario')->FindAll();

            for($i = 0; $i < 6; ++$i){
                $entidad = new Venta();

                $oferta = $ofertas[array_rand($ofertas)];
                $entidad->setOferta($oferta);

                $usuario = $usuarios[array_rand($usuarios)];
                $entidad->setUsuario($usuario);

                $oferta->setCompras($oferta->getCompras() + 1);

                $entidad->setFecha($this->getFecha($oferta->getFechaPublicacion()));

                $manager->persist($entidad);
            }
        $manager->flush();
    }

        private function getFecha($fecha)
        {
            date_default_timezone_set('Europe/Madrid');
            $now = strtotime(date('Y-m-d H:i:s'));
            $fecha = strtotime($fecha->format('Y-m-d H:i:s'));

            $fechaCompra = new \DateTime(date('Y-m-d H:i:s', mt_rand($fecha, $now)));
            return $fechaCompra;
        }
   
}