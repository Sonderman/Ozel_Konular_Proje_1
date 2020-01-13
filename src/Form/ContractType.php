<?php

namespace App\Form;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('customer_id')
            //->add('car_id')
            ->add('pick_up_date')
            ->add('drop_off_date')
            ->add('pick_up_location')
            ->add('drop_off_location')
            ->add('status',ChoiceType::class,[
                'choices'=>[
                    'New'=>'New',
                    'Accepted'=>'Accepted',
                    'Canceled'=>'Canceled',
                    'Completed'=>'Completed',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }
}
