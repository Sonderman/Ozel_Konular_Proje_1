<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parentid')
            ->add('title')
            ->add('keywords')
            ->add('description')
            ->add('image',FileType::class,[
                'label' => 'Category Image',
                'mapped'=> false,
                'required'=> false,
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
            ->add('status',ChoiceType::class,[
                'choices'=>[
                    'Active'=>'active',
                    'Passive'=>'passive'
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
