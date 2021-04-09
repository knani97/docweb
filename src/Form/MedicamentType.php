<?php

namespace App\Form;

use App\Entity\Medicament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MedicamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'required' => true])
            ->add('fournisseur',TextType::class,[
                'required' => true])
            ->add('prix_achat',TextType::class,[
                'required' => true])
            ->add('poid',TextType::class,[
                'required' => true])
            ->add ('img', FileType::class, [
                'label' => "Importer l'image du médicament",
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
            'data_class' => Medicament::class,
        ]);
    }
}
