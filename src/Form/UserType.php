<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, $this->getConfiguration("Email", "camilo@gmail.com"))
            ->add('picture', FileType::class, $this->getConfiguration('Photo', 'Télécharger une photo ...'))
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "Mon prénom..."))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Mon nom..."))
            ->add('titlePresentation', TextType::class, $this->getConfiguration("Titre", "Titre présentation ..."))
            ->add('presentation', TextareaType::class, $this->getConfiguration('Présentation', 'Présentation perso ...'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
