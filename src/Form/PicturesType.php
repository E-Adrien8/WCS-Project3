<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PicturesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', FileType::class, options: [
                'mapped' => false,
                'label' => 'Ajouter une photo (jpg, jpeg ou png - max : 6M)',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '6M',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid jpg/jpeg/png image',
                    ])
                ]])
            ->add('Ajouter', SubmitType::class, [
                'attr' => ['class' => 'btn btn-warning']
            ]);
    }
}
