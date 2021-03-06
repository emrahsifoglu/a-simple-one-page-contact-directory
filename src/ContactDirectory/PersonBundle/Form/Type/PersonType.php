<?php

namespace ContactDirectory\PersonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id','integer');
        $builder->add('firstName', 'text');
        $builder->add('lastName', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class'        => 'ContactDirectory\PersonBundle\Model\PersonModel',
                'csrf_protection'   => false,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'person';
    }

} 