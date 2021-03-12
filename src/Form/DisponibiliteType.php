<?php

namespace App\Form;

use App\Entity\Disponibilite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisponibiliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date Début',
                'input' => 'datetime',
                'widget' => 'single_text',
                'data' => new \DateTime("now")
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Date Fin',
                'input' => 'datetime',
                'widget' => 'single_text',
                'data' => new \DateTime("now")
            ])
            ->add('dureeRDV', TimeType::class, [
                'input' => 'datetime',
                'widget' => 'single_text',
                'data' => new \DateTime("now")
            ])
            ->add('dureePause', TimeType::class, [
                'input' => 'datetime',
                'widget' => 'single_text',
                'data' => new \DateTime("now")
            ])
            ->add("submit", SubmitType::class, [
                'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Disponibilite::class,
        ]);
    }
}
