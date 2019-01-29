<?php

namespace App\Form;

use App\Entity\Contact;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class,
                [   'label'  => 'Nom/prénom',
                    'attr' => [
                        'class' => 'text-box',
                        'placeholder' => 'Nom/prénom'
                    ]])
            ->add('email', EmailType::class,
                [ 'label'=> 'Email',
                    'attr' => [
                        'class' => 'text-box',
                        'placeholder' => 'Votre courriel'
                    ]
                ])
            ->add('message', TextareaType::class,
                [ 'label'=> 'message',
                    'attr' => [
                        'class' => 'text-box',
                        'placeholder' => 'votre message'
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }

}
