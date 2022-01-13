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
                    'placeholder'=>'Rechercher un prénom'
                    ]
                ]
            )
            ->add('qLastName', TextType::class, [
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Rechercher un nom'
                    ]
                ]
            )
            ->add('qGroupName', TextType::class, [
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Rechercher une classe'
                    ]
            ])
            ->add('clear', SubmitType::class,[
                'label'=>false,
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
