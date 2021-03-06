<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * UsuarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsuarioRepository extends \Doctrine\ORM\EntityRepository
{
    public function findTodasLasCompras($usuario)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('
            SELECT v, o, t
            FROM AppBundle:Venta v
            JOIN v.oferta o
            JOIN o.tienda t
            WHERE v.usuario = :id
            ORDER BY v.fecha DESC');
        $consulta->setParameter('id', $usuario);
        return $consulta->getResult();
    }

}
