<?php

namespace App\Domain\Account\Form;

use App\Domain\Account\Dto\LoginDto;
use Symfony\Component\Form\AbstractType;
use App\Domain\Account\Dto\RegistrationDto;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usernameOrEmail', TextType::class)
            ->add('plainPassword', PasswordType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LoginDto::class,
        ]);
    }
}
