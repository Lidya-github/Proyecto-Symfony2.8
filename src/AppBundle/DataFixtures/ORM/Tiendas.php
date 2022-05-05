<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Tienda;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Tiendas extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        $num=0;
        $ciudades = $manager->GetRepository('AppBundle:Ciudad')->FindAll();
        foreach ($ciudades as $ciudad) {
            for($i = 0; $i < 15; ++$i){
                $entidad = new Tienda();
                $num ++;

                $entidad->setNombre($this->getNombre());
                
                $entidad->setLogin('tienda'.$num);
               
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($entidad);
                $passwordEnClaro = 1234;
                $passwordCodificado = $encoder->encodePassword($passwordEnClaro,$entidad->getSalt());
                $entidad->setPassword($passwordCodificado);


                $entidad->setDescripcion($this->getDescripcion());

                $entidad->setDireccion($this->getDireccion());

                $ciudad = $ciudades[array_rand($ciudades)];
                $entidad->setCiudad($ciudad);

                $manager->persist($entidad);
            }
        }



        $manager->flush();
    }

    private function getNombre()
    {
        $palabras = array_flip(array(
            'Lorem', 'Ipsum', 'Sitamet', 'Et', 'At', 'Sed', 'Aut', 'Vel', 'Ut',
            'Dum', 'Tincidunt', 'Facilisis', 'Nulla', 'Scelerisque', 'Blandit',
            'Ligula', 'Eget', 'Drerit', 'Malesuada', 'Enimsit', 'Libero',
            'Penatibus', 'Imperdiet', 'Pendisse', 'Vulputae', 'Natoque',
        ));

        return implode (' ', array_rand($palabras, mt_rand(2, 4)));
    }

    private function getDescripcion()
    {
        $desc = array_flip(array(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'Sed at fringilla orci. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
            'Integer turpis lorem, porta non enim nec, molestie tempor dolor.',
            'Morbi ac vulputate lacus, sit amet efficitur sapien.',
            'Cras ornare maximus odio, vel congue tortor luctus ac.',
            'Cras sodales enim odio, eget blandit elit tempus at.',
        ));

        return implode(' ', array_rand($desc, mt_rand(2,4)));
    }

    private function getDireccion()
    {
        $direcciones = array_flip(array(
            'Calle Villalón 3',
            'Avenida de las provincias 5',
            'Calle De Huesca 3, 5A',
            'Calle Gallur 13',
            'Avenida de la Hispanidad 4',
        ));

        return array_rand($direcciones);
    }
}