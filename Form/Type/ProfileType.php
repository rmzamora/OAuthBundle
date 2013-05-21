<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Rmzamora\OAuthBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Rmzamora\OAuthBundle\Form\DataTransformer\ArrayToJSONStringTransformer;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ArrayToJSONStringTransformer();
        $builder
            ->add('firstname', null, array('label' => 'form.firstname',
            'translation_domain' => 'RmzamoraSonataExtUserBundle'))

            ->add('lastname', null, array('label' => 'form.lastname',
            'translation_domain' => 'RmzamoraSonataExtUserBundle'))

            ->add('website', null, array('label' => 'form.website',
            'translation_domain' => 'RmzamoraSonataExtUserBundle'))

            ->add('phone', null, array('required' => false,
                                        'label' => 'form.phone',
                                        'translation_domain' => 'RmzamoraSonataExtUserBundle'))

            ->add('dateOfBirth', 'rmzamora_custom_datepicker', array('label' => 'form.dateOfBirth',
            'translation_domain' => 'RmzamoraSonataExtUserBundle'))

            ->add('gender', 'choice', array('label' => 'form.gender',
                                            'translation_domain' => 'RmzamoraSonataExtUserBundle',
                                            'choices'=>array('male'=>'Male', 'female'=>'Female')))

            ->add('biography', 'textarea', array('label' => 'form.biography',
                                                 'required' => false,
                                                 'translation_domain' => 'RmzamoraSonataExtUserBundle',))

            ->add('locale', 'hidden', array())
            ->add('timezone', 'hidden', array())

            ->add('facebookUid', 'hidden', array())
            ->add('facebookName', 'hidden', array())
            ->add($builder->create('facebookData', 'hidden', array())->addModelTransformer($transformer))

            ->add('twitterUid', 'hidden', array())
            ->add('twitterName', 'hidden', array())
            ->add($builder->create('twitterData', 'hidden', array())->addModelTransformer($transformer))

            ->add('gplusUid', 'hidden', array())
            ->add('gplusName', 'hidden', array())
            ->add($builder->create('gplusData', 'hidden', array())->addModelTransformer($transformer))

            ->add('linkedinUid', 'hidden', array())
            ->add('linkedinName', 'hidden', array())
            ->add($builder->create('linkedinData', 'hidden', array())->addModelTransformer($transformer))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rmzamora_oauth_user_profile';
    }
}
