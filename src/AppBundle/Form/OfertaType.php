<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class OfertaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nombre')
        ->add('descripcion')
        ->add('condiciones')
        ->add('foto', 'file', array('required' => false))
        ->add('precio', 'money')
        ->add('descuento', 'money')
        ->add('umbral')
        ->add('fechaExpiracion','datetime')
        ->add('compras')
        ->add('guardar', 'submit', array('label' => 'Guardar cambios'));
        

            if (true === $options['mostrar_condiciones']) {
                // Cuando se crea una oferta, se muestra un checkbox para aceptar las
                // condiciones de uso
                $builder->add('acepto', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', array(
                    'mapped' => false,
                    'constraints' => new IsTrue(array(
                        'message' => 'Debes aceptar las condiciones indicadas antes de poder añadir una nueva oferta',
                    )),
                ));
            }
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Oferta',
            'mostrar_condiciones' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_oferta';
    }


}
