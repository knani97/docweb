<?php

namespace App\Form;

use App\Entity\Pharmacie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PharmacieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'required' => true
            ])
            ->add('adr',TextType::class,[
                'required' => true
            ])
            ->add('gouv',TextType::class,[
                'required' => true
            ])
            ->add('img_pat', FileType::class, [
                'label' => "Importer l'image de la pattente",
                'attr' => [
                    'accept' => 'image/jpeg, image/jpg, image/png, image/gif'
                ],
                'data_class' => null,
                'required' => true
            ]
        )
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pharmacie::class,
        ]);
    }
}
