<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Usuario;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class Usuarios extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public function getOrder()
    {
        return 4;
    }

    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $ciudades = $manager->GetRepository('AppBundle:Ciudad')->FindAll();
        foreach($ciudades as $ciudad){
            
            for($i = 0; $i < 6; $i++){
                $entidad = new Usuario();
                
                $entidad->setCiudad($ciudad);

                $entidad->setNombre($this->getNombre());
                $entidad->setApellidos($this->getApellidos());

                $entidad->setEmail($this->getEmail($entidad->getNombre()));

                $encoder = $this->container->get('security.encoder_factory')->getEncoder($entidad);
                $passwordEnClaro = '1234';
                $password = $encoder->encodePassword($passwordEnClaro, null);
                $entidad->setPassword($password);

                $entidad->setDireccion($this->getDireccion());

                $entidad->setPermiteEmail((mt_rand(1, 1000) % 10) < 7);

                $fecha = 'now -'. mt_rand(1, 200). ' days';
                $fechaAlta = new \DateTime($fecha);
                
                $entidad->setFechaAlta($fechaAlta);

                $entidad->setFechaNacimiento($this->getFechaNacimiento());

                $entidad->setDni($this->getDni());

                $entidad->setNumeroTarjeta($this->getTarjeta());

                $manager->persist($entidad);
            }
        }

        $manager->flush();
    }

    private function getNombre()
    {
        $nombres = array_flip(array(
            'Jose', 'Carlos', 'Sergio', 'Luis', 'Raúl', 'María', 'Esteban',
            'Silvia', 'Lidya', 'Lorena', 'Miriam', 'Damián',
        ));

        return array_rand($nombres);
    }

    private function getApellidos()
    {
        $apellidos = array_flip(array(
            'López', 'Araque', 'García', 'Ortega', 'Gutierrez', 'Martinez', 'Nogales',
            'Hernandez', 'Martín', 'Vallejo', 'Sualdea',
        ));

        return implode(' ', array_rand($apellidos, 2));
    }

    private function getEmail($nombre){
        $mail = array_flip(array(
            'gmail.com', 'hotmail.com', 'protonmail.com', 'outlook.com', 'yandex.com'
        ));

        return $nombre.'@'.array_rand($mail);
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

    private function getFechaNacimiento()
    {
        $min = strtotime('1970-01-01');
        $max = strtotime('2002-01-01');

        $fecha = new \DateTime(date('Y-m-d', rand($min, $max)));
        return $fecha;
    }

    private function getDni()
    {
        $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $num = '';
        for($i = 0; $i < 8; $i++){
            $num = strval($num). mt_rand(0,9);
        }
        $dni = strval($num). $letras[$num%23];
        return $dni;
    }

    private function getTarjeta()
    {
        $num = '';
        for($i = 0; $i < 16; $i++){
            $num = strval($num). mt_rand(0,9);
        }
        return $num;
    }

}