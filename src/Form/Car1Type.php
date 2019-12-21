<?php

namespace App\Form;

use App\Entity\Car;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class Car1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('keywords')
            ->add('description')

            //resim almak için burası ekledi

            ->add('image',FileType::class,[
                'label' => 'Car Image',
                'mapped'=> false,
                'required'=> true,
                'constraints'=> [
                    new File([
                        'maxSize'=>'4096k',
                        'mimeTypes'=>[
                            'image/*',
                        ],
                        'mimeTypesMessage'=>'Lütfen geçerli bir resim dosyası yükleyin!',
                    ])
                ]
            ])

            ->add('rate')
            ->add('seats')
            ->add('doors')
            ->add('has_airconditions')
            ->add('gearbox')
            ->add('transmission')
            ->add('fuel_type')
            ->add('baggage_capacity')
            ->add('brand')
            ->add('model')
            ->add('year')
            ->add('price_for_a_day')
            // ->add('owner_id')
            ->add('category')
            //  ->add('owner')
            //  ->add('contract')

            ->add('detail',CKEditorType::class,array(
                'config'=>array(
                    'uiColor'=>'#ffffff',
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
