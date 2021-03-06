<?php
namespace AppBundle\Tests\Twig\Extension;

use AppBundle\Twig\Extension\CuponExtension;

    class TwigExtensionTest extends \PHPUnit_Framework_TestCase
    {
        public function testDescuento()
        {
            
            $extension = new CuponExtension();
            $this->assertEquals('-', $extension->descuento(100, null),
                'El descuento no puede ser null'
            );

            $this->assertEquals('-', $extension->descuento('a', 3),
                'El precio debe ser un número'
            );
            $this->assertEquals('-', $extension->descuento(100, 'a'),
                'El descuento debe ser un número'
            );
            $this->assertEquals('0%', $extension->descuento(10, 0),
                'Un descuento de cero euros se muestra como 0%'
            );
            $this->assertEquals('-80%', $extension->descuento(2, 8),
                'Si el precio de venta son 2 euros y el descuento sobre el precio
                original son 8 euros, el descuento es -80%'
            );
            $this->assertEquals('-33%', $extension->descuento(10, 5),
                'Si el precio de venta son 10 euros y el descuento sobre el precio
                original son 5 euros, el descuento es -33%'
            );
            $this->assertEquals('-33.33%', $extension->descuento(10, 5, 2),
                'Si el precio de venta son 10 euros y el descuento sobre el precio
                original son 5 euros, el descuento es -33.33% con dos decimales'
            );
        }

        public function testMostrarComoLista()
        {
            $fixtures = __DIR__.'/fixtures/lista';
            $extension = new CuponExtension();

            $original = file_get_contents($fixtures.'/original.txt');

            $this->assertEquals(
                file_get_contents($fixtures.'/esperado-ul.txt'),
                $extension->mostrarComoLista($original)
            );

            $this->assertEquals(
                file_get_contents($fixtures.'/esperado-ol.txt'),
                $extension->mostrarComoLista($original, 'ol')
        );
        }

    }
