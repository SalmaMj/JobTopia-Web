<?php

namespace App\Form;

use App\Entity\Offres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Categorie;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Vich\UploaderBundle\Form\Type\VichImageType;


class OffresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre', TextType::class)
        ->add('description', TextareaType::class)
        ->add('categorie', ChoiceType::class, [
            'choices' => array_combine(Categorie::getValues(), Categorie::getValues()),
            'placeholder' => 'Choisir une catégorie',
        ])
        ->add('skill1', TextType::class, [
            'required' => false,
        ])
        ->add('skill2', TextType::class, [
            'required' => false,
        ])
        ->add('skill3', TextType::class, [
            'required' => false,
        ]) 
        ->add('clientid', TextType::class)
        ->add('logoPath', FileType::class)        
        
        ->add('dl', DateType::class,
        [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'html5' => false,
                'attr' => ['class' => 'form-control datepicker'],
            'label' => 'date limite de l\'offre',
        ])
        ->getForm();

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offres::class,
        ]);
    }
}
