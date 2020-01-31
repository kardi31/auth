<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' => [
                        'ROLE_USER',
                        'ROLE_ADMIN_USER',
                        'ROLE_API_USER'
                    ],
                    'multiple' => true,
                    'empty_data' => [],
                    'required' => true,
                ])
            ->add('password');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'csrf_protection' => false,
                'data_class' => User::class,
            ]
        );
    }
}
