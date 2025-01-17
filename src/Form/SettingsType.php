<?php

namespace App\Form;

use App\Entity\Settings;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('keywords')
            ->add('description')
            ->add('company')
            ->add('address')
            ->add('phone')
            ->add('fax')
            ->add('email')
            ->add('smtpserver')
            ->add('smtpemail')
            ->add('smtppassword')
            ->add('smtpport')
            ->add('facebook')
            ->add('instagram')
            ->add('twitter')

            ->add('aboutus',CKEditorType::class,array(
                'config'=>array('uiColor'=>'#ffffff',),))

            ->add('status',ChoiceType::class,[
                'choices'=>[
                    'True'=>'True',
                    'False'=>'False'
                ],
            ])

            ->add('contact',CKEditorType::class,array(
                'config'=>array('uiColor'=>'#ffffff',),))

            ->add('reference',CKEditorType::class,array(
                'config'=>array('uiColor'=>'#ffffff',),))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
        ]);
    }
}
