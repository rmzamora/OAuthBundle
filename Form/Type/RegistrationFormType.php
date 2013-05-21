<?php
/**
 * This file is part of the JetBrains PhpStorm.
 *
 * (c) mell m. zamora <me@mellzamora.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rmzamora\OAuthBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\Doctrine\Types\JsonType;
use Rmzamora\OAuthBundle\Form\DataTransformer\ArrayToJSONStringTransformer;

class RegistrationFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
                $transformer = new ArrayToJSONStringTransformer();
                $builder
                    ->add('username', null, array('label' => 'form.username',
                    'translation_domain' => 'RmzamoraOAuthBundle'))

                    ->add('email', 'email', array('label' => 'form.email',
                    'translation_domain' => 'RmzamoraOAuthBundle'))

                    ->add('plainPassword', 'repeated', array(
                            'type' => 'password',
                            'options' => array('translation_domain' => 'RmzamoraOAuthBundle'),
                            'first_options' => array('label' => 'form.password'),
                            'second_options' => array('label' => 'form.password_confirmation'),
                            'invalid_message' => 'fos_user.password.mismatch',
                        ))

                    ->add('firstname', null, array('label' => 'form.firstname',
                            'translation_domain' => 'RmzamoraOAuthBundle'))

                    ->add('lastname', null, array('label' => 'form.lastname',
                        'translation_domain' => 'RmzamoraOAuthBundle'))

                    ->add('website', null, array('label' => 'form.website',
                        'translation_domain' => 'RmzamoraOAuthBundle'))

                    ->add('dateOfBirth', 'rmzamora_custom_datepicker', array('label' => 'form.dateOfBirth',
                        'translation_domain' => 'RmzamoraOAuthBundle'))

                    ->add('gender', 'choice', array('label' => 'form.gender',
                        'translation_domain' => 'RmzamoraOAuthBundle',
                        'choices'=>array('m'=>'Male', 'f'=>'Female')))

                    ->add('biography', 'textarea', array('label' => 'form.biography',
                            'required' => false,
                            'translation_domain' => 'RmzamoraSonataExtUserBundle',))

                    ->add('locale', 'hidden', array())
                    ->add('timezone', 'hidden', array())

//                    ->add('facebookUid', 'hidden', array())
//                    ->add('facebookName', 'hidden', array())
//                    ->add($builder->create('facebookData', 'hidden', array())->addModelTransformer($transformer))
//
//                    ->add('twitterUid', 'hidden', array())
//                    ->add('twitterName', 'hidden', array())
//                    ->add($builder->create('twitterData', 'hidden', array())->addModelTransformer($transformer))
//
//                    ->add('gplusUid', 'hidden', array())
//                    ->add('gplusName', 'hidden', array())
//                    ->add($builder->create('gplusData', 'hidden', array())->addModelTransformer($transformer))
//
//                    ->add('linkedinUid', 'hidden', array())
//                    ->add('linkedinName', 'hidden', array())
//                    ->add($builder->create('linkedinData', 'hidden', array())->addModelTransformer($transformer))
//                    ->add($builder->create('roles', 'hidden')->addModelTransformer($transformer))
                ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'registration',
        ));
    }

    public function getName()
    {
        return 'rmzamora_oauth_registration';
    }
}
