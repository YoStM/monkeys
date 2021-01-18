<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UpdateProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'attr' => [
                    'rows' => 7,
                    'cols' => 63
                ]
            ])
            ->add('active', ChoiceType::class, [
                'choices' => [
                    'Ouvert' => true,
                    'Clôturé' => false
                ],
                'attr' => [
                    'class' => 'custom-select'
                ]
            ])
            ->add('CategoryId', EntityType::class, [
                'label' => 'Compétence recherchée :',
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder("c")
                        ->orderBy("c.label");
                },
                'choice_label' => 'label'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
