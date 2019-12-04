<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CarType extends AbstractType
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
                        'maxSize'=>'1024k',
                        'mimeTypes'=>[
                            'image/*',
                        ],
                        'mimeTypesMessage'=>'Lütfen geçerli bir resim dosyası yükleyin!',
                    ])
                ]
            ])
            ->add('status')
            ->add('created_at')
            ->add('updated_at')
            ->add('category_id')
            ->add('contract_id')
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
            ->add('owner_id')
            ->add('category')
            ->add('owner')
            ->add('contract')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
