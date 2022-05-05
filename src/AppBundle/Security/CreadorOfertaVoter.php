<?php
namespace AppBundle\Security;

use AppBundle\Entity\Oferta;
use AppBundle\Entity\Tienda;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

    class CreadorOfertaVoter extends Voter
    {
        public function supports($attribute, $subject)
        {
            return $subject instanceof Oferta && 'ROLE_EDITAR_OFERTA' === $attribute;
        }

        protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
        {
            $tienda = $token->getUser();
            return $tienda instanceof Tienda && $subject->getTienda()->getId() === $tienda->getId();
        }

    }
