<?php

namespace App\Form;

use App\Entity\Pharmacie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PharmacieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, array(
                'label' => 'Nom de la pharmacie : '))
            ->add('adresse',TextType::class, array(
                'label' => 'Adresse de la pharmacie : '))
            ->add('gerant',TextType::class, array(
                'label' => 'Gerant: '))
            ->add('telephne',NumberType::class)
            ->add('heure_ouverture')
            ->add('heure_fermeture')
            ->add('type_pharmacie',TextType::class, array(
                'label' => 'Type de la pharmacie : '))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pharmacie::class,
        ]);
    }
}
