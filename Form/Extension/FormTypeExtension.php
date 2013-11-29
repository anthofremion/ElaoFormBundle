<?php

namespace Elao\Bundle\FormBundle\Form\Extension;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Elao\Bundle\FormBundle\Form\Extension\TreeAwareExtension;
use Elao\Bundle\FormBundle\Service\FormTreebuilder;
use Elao\Bundle\FormBundle\Service\FormKeybuilder;

class FormTypeExtension extends TreeAwareExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('help' => false));

        if ($this->autoGenerate) {
            $resolver->replaceDefaults(array('label' => true));
        }
    }
}