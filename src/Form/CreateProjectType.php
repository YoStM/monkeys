<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'attr' => [
                    'class' => 'my-3'
                ]
            ])
            ->add('CategoryId', EntityType::class, [
                'label' => 'Compétence recherchée :',
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder("c")
                        ->orderBy("c.label");
                },
                'choice_label' => 'label',
                'attr' => [
                    'class' => 'my-3'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'attr' => [
                    'class' => 'my-3',
                    'rows' => 7,
                    'cols' => 63
                ]
            ])
            //->add('createDate')
            //->add('modifyDate')
            //->add('active')
            //->add('UserId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
