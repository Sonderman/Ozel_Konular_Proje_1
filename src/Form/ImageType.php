<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Image;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
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
            ->add('car',EntityType::class,[
                'class' => Car::class,
                'choice_label'=>'title',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'csrf_protection'=> false,
        ]);
    }
}
