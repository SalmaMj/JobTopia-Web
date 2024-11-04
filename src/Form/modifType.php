<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Offres;
use App\Entity\Categorie;

class modifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class)
            ->add('description', TextareaType::class)
            ->add('categorie', ChoiceType::class, [
                'choices' => array_combine(Categorie::getValues(), Categorie::getValues()),
                'placeholder' => 'Choose a category',
            ])
            ->add('dl', DateType::class)
            ->add('dc', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'html5' => false,
                'attr' => ['class' => 'form-control datepicker', 'readonly' => true],
                'disabled' => true,
            ])
            ->add('skill1', TextType::class, [
                'required' => true,
            ])
            ->add('skill2', TextType::class, [
                'required' => false,
            ])
            ->add('skill3', TextType::class, [
                'required' => false,
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offres::class,
        ]);
    }
}

