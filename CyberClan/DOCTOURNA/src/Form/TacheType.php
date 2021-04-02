<?php

namespace App\Form;

use App\Entity\Tache;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('date', DateTimeType::class, [
                'input' => 'datetime',
                'widget' => 'single_text',
            ])
            ->add('duree', TimeType::class, [
                'input' => 'datetime',
                'widget' => 'single_text'
            ])
            ->add('description')
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Rendez-vous Perso' => 5,
                    'Prise medicament' => 2,
                    'Personnelle' => 3,
                    'Disponibilite' => 4
                ]
            ])
            ->add('couleur', ColorType::class, [
                'attr' => array('style' => 'width: 200px')
            ])
            ->add("submit", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
    }
}
