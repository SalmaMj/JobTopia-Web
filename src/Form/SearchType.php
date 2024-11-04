<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType as SearchInputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchInputType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Mots clés...']
            ])
            ->add('categorie', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Choisir une catégorie',
                'choices' => array_combine(Categorie::getValues(), Categorie::getValues())
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $search = $data['search'];
                $event->setData([
                    'search' => $search,
                    'categorie' => $data['categorie']
                ]);
            });
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_protection' => false,
        ]);
    }
}
