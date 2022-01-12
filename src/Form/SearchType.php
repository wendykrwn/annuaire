<?php

namespace App\Form;

use App\Entity\Group;
use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('qFirstName', TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Search by firstName'
                    ]
                ]
            )
            ->add('qLastName', TextType::class, [
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Search by lastName'
                    ]
                ]
            )
            ->add('qGroupName', TextType::class, [
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Search by group name'
                    ]
            ])
            ->add('clear', SubmitType::class,[
                'label'=>'Reset search',
                'attr'=>[
                    'class'=>'mt-2 btn btn-danger w-100'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'=>SearchData::class,
            'method'=>'Get',
            'csrf_protection'=>false
        ]);
    }
}
