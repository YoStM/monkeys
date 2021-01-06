<?php

namespace App\Form;

use App\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom :'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom :'
            ])
            ->add('companyName', TextType::class, [
                'label' => 'Nom de La société :'
            ])
            ->add('siret', TextType::class, [
                'label' => 'Numérot de Siret :'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email :'
            ])
            ->add('activity', TextType::class, [
                'label' => 'Profession :'
            ])
            ->add('aboutUser', TextareaType::class, [
                'label' => 'Parlez nous de vous et de votre univers :'
            ])
            //->add('credit')
            //->add('userId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
