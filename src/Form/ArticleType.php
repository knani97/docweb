<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\ArticleCat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,[
                'attr'=>['placeholder' => 'ajouter un titre']
            ])
            ->add('idCat',EntityType::class,[
                'class'=>ArticleCat::class,
                'choice_label'=>'categorie',
                'empty_data' => 'Veuillez sélectionner une catagorie',
                'attr' => [
                    'class' => 'input--style-5'
                ]
            ])

            ->add('text',TextareaType::class, [
                'attr' => ['cols' => '55', 'rows' => '3','placeholder'=>"Entrer le titre darticle",
                    'placeholder' => 'Ajouter votre contenu']
            ])
            ->add('image',FileType::class,
                ['data_class' => null])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
