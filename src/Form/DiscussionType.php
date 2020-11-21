<?php

namespace App\Form;

use App\Entity\Discussion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscussionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => ['placeholder' => 'Titre ou theme de l\'exercice', 'name' => 'titre']
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Ajouter une image',
                'attr' => ['placeholder' => 'azerty', 'class' => 'file-input', 'name' => 'image']
            ])
            ->add('question', TextareaType::class, [
                'attr' => ['placeholder' => 'Votre exercice ou un commentaire', 'name' => 'contenu'],
                'label' => 'Contenu'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Discussion::class,
        ]);
    }
}
