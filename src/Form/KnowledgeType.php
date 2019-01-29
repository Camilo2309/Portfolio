<?php

namespace App\Form;

use App\Entity\Knowledge;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KnowledgeType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration("Nom", "Nom des technos ..."))
            ->add('picture', FileType::class, $this->getConfiguration('Photo', 'Photo de la techno...'))
            ->add('description', TextareaType::class, $this->getConfiguration('Description', 'Descirption de ce que je sais...'))
            ->add('rating', IntegerType::class, $this->getConfiguration('Note' , 'Note tes connaissances de 1 à 5...'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Knowledge::class,
        ]);
    }
}
