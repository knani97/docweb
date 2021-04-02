<?php

namespace App\Form;

use App\Entity\RDV;
use App\Entity\Tache;
use App\Repository\TacheRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RDVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medecin', EntityType::class, array(
                'class' => 'App\Entity\User',
                'query_builder' => function(UserRepository $repo) {
                    return $repo->createMedQueryBuilder();
                },
                'placeholder' => 'Choisir un medecin',
                ))
            ->add('submit', SubmitType::class, [
                'label' => 'Voir RDVs dispo'
            ])
        ;

        $builder->get('medecin')->addEventListener(FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
            $form = $event->getForm();

            $form->getParent()->add('tacheDispo', EntityType::class, [
                'class' => 'App\Entity\Tache',
                'label' => 'RDVs Disponibles',
                'placeholder' => 'Choisir une date qui vous conviens',
                'query_builder' => function(TacheRepository $repo) use ($event) {
                    return $repo->createRDVQueryBuilder($event->getData());
                },
            ])->add('description', TextareaType::class)
                ->add('submit', SubmitType::class, [
                    'label' => 'Valider'
                ])
                ;
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RDV::class,
        ]);
    }
}
