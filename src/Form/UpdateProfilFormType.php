<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => '<strong>Loisirs, centre d\'intérets (facultatif) :</strong>',
                'label_html' => true,
                'required' => false,
            ])
            ->add('userName', TextareaType::class, [
                'label' => '<strong>Pseudo :</strong>',
                'label_html' => true,
                'required' => false,
            ])
            ->add('facebook', TextareaType::class, [
                'label' => '<p class="bi bi-facebook"> Facebook</p>',
                'label_html' => true,
                'required' => false,
            ])
            ->add('instagram', TextareaType::class, [
                'label' => '<p class="bi bi-instagram"> Instagram</p>',
                'label_html' => true,
                'required' => false,
            ])
            ->add('picture', FileType::class, [
                'mapped' => false,
                'label' => '<strong>Photo de profil</strong>',
                'label_html' => true,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4M',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid jpg/jpeg/png image',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
