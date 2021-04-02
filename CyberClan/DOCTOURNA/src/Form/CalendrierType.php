<?php

namespace App\Form;

use App\Entity\Calendrier;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('format', ChoiceType::class, [
                'choices'  => [
                    'Plage Horaire' => 1,
                    'Calendrier Standard' => 2,
                ]
            ])
            ->add('couleur', ColorType::class, [
                'attr' => array('style' => 'width: 200px')
            ])
            ->add('timezone', TimezoneType::class, [
                'attr' => array('style' => 'width: 200px')
            ])
            ->add('email', CheckboxType::class, [
                'label'=> 'Notification Email (activer cette option pour les rappels importants)',
                'required' => false
            ])
            ->add("submit", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
        ]);
    }
}
