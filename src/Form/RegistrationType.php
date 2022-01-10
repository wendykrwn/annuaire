<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('groupName', EntityType::class, [
                'class' => Group::class,
                'choice_label' => function (Group $group){
                    return $group->getName() . ' ' . $group->getPromo();
                }
            ])
            ->add('address')
            ->add('city')
            ->add('alternanceJob')
            ->add('birthDate', BirthdayType::class, [
                'format' => 'ddMMyyyy',
                'placeholder' => [
                    'year' => 'année',
                    'month' => 'mois',
                    'day' => 'jour'
                ],
                'html5'=> false
            ])
            ->add('image')
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
