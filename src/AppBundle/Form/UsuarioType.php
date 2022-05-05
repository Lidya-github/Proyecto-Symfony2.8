<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

    class UsuarioType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('nombre')->add('apellidos')->add('email')->add('direccion')->add('permiteEmail')->add('fechaNacimiento','birthday')
                    ->add('dni')->add('numeroTarjeta')->add('ciudad')
                    ->add('permiteEmail', 'checkbox', array('required' => false));
            
            if ('crear_usuario' === $options['accion']) {
                $builder->add('registrarme', 'submit', array(
                    'label' => 'Registrarme',
                ))
                ->add('passwordEnClaro', 'repeated', array(
                    'type' => 'password',
                    'required' => true,
                    'invalid_message' => 'Las dos contraseñas deben coincidir',
                    'first_options' => array('label' => 'Contraseña'),
                    'second_options' => array('label' => 'Repite Contraseña'),
                ));
            } elseif ('modificar_perfil' === $options['accion']) {
                $builder->add('guardar', 'submit', array(
                    'label' => 'Guardar cambios',
                ))
                ->add('passwordEnClaro', 'repeated', array(
                    'type' => 'password',
                    'required' => false,
                    'invalid_message' => 'Las dos contraseñas deben coincidir',
                    'first_options' => array('label' => 'Contraseña'),
                    'second_options' => array('label' => 'Repite Contraseña'),
                ));
            }
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'AppBundle\Entity\Usuario',
                'accion' => 'modificar_perfil',
            ));    
        }

        public function getBlockPrefix()
        {
            return 'usuario';
        }

    }

