<?php

namespace App\Form;

use App\Entity\RDV;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReporterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, [
                'input' => 'datetime',
                'widget' => 'single_text',
                'data' => new \DateTime("now"),
                'years' => range(date('Y'), date('Y')+100),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31),
            ])
            ->add('description', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'label' => 'Raison',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Reporter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RDV::class,
        ]);
    }
}
