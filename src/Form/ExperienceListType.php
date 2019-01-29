<?php

namespace App\Form;

use App\Entity\ExperienceList;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceListType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, $this->getConfiguration('Contenue', 'Contenu des experiences...'))
            ->add('enterprise', TextType::class, $this->getConfiguration('Entreprise', "Nom de l'entreprise..."))
            ->add('city', TextType::class, $this->getConfiguration('Ville et pays', 'Nom de la ville et du pays ...'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExperienceList::class,
        ]);
    }
}
