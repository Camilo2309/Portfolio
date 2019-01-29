<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Titre du projet ..."))
            ->add('content', TextType::class, $this->getConfiguration('Contenu', 'Décrire le projet ...'))
            ->add('url', UrlType::class, $this->getConfiguration('Lien', 'Lien vers le projet ...'))
            ->add('picture', FileType::class, $this->getConfiguration('Photo', 'Télécharger une photo du projet ...'))
            ->add('technology', CollectionType::class,
                [
                    'entry_type' => KnowledgeType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
