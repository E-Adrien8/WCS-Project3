<?php

namespace App\Form;

use App\Entity\FoodType;
use App\Entity\Restaurant;
use App\Entity\Zone;
use App\Repository\FoodTypeRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RestaurantAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du restaurant',
                'required' => true,
                'attr' => ['placeholder' => 'Nom du restaurant'],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => true,
                'attr' => ['placeholder' => 'Adresse'],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Code postal',
                'attr' => ['placeholder' => 'Code postal'],
                'required' => true,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Ville'],
                'required' => true,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => true,
                'attr' => ['placeholder' => 'Numéro de téléphone'],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'required' => true,
                'attr' => ['placeholder' => 'Adresse e-mail'],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('zone', EntityType::class, [
                'class' => Zone::class,
                'choice_label' => 'city',
                'label' => 'Zone',
                'attr' => ['placeholder' => 'Type de restaurant'],
                'required' => true,
            ])
            ->add('foodType', EntityType::class, [
                'class' => FoodType::class,
                'query_builder' => function (FoodTypeRepository $foodTypeRepository) {
                    return $foodTypeRepository->createQueryBuilder('f')
                        ->orderBy('f.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'Type de cuisine',
                'attr' => ['placeholder' => 'Type de cuisine'],
                'required' => false,
            ])
            ->add('chefName', TextType::class, [
                'label' => 'Nom du chef',
                'attr' => ['placeholder' => 'Nom du chef'],
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Description'],
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('menuText', TextareaType::class, [
                'label' => 'Menu ou lien URL de la carte',
                'attr' => ['placeholder' => 'Menu ou lien URL de la carte'],
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('menuPdf', FileType::class, [
                'mapped' => false,
                'label' => 'Menu PDF (max : 1024k)',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('mainPicture', FileType::class, [
                'mapped' => false,
                'label' => 'Photo de couverture (jpg/jpeg/png - max : 6M)',
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
                ],
            ])
            ->add('websiteLink', UrlType::class, [
                'label' => 'Lien site internet',
                'default_protocol' => 'http',
                'attr' => ['placeholder' => 'Lien site internet'],
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('tripadvisorLink', UrlType::class, [
                'label' => 'Lien TripAdvisor',
                'default_protocol' => 'http',
                'attr' => ['placeholder' => 'Lien TripAdvisor'],
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('facebookLink', UrlType::class, [
                'label' => 'Lien Facebook',
                'default_protocol' => 'http',
                'attr' => ['placeholder' => 'Lien Facebook'],
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('instagramLink', UrlType::class, [
                'label' => 'Lien Instagram',
                'default_protocol' => 'http',
                'attr' => ['placeholder' => 'Lien Instagram'],
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('siret', TextType::class, [
                'label' => 'SIRET',
                'attr' => ['placeholder' => 'SIRET'],
                'required' => true,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('billingAddress', TextType::class, [
                'label' => 'Adresse de facturation',
                'attr' => ['placeholder' => 'Adresse de facturation'],
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('vat', TextType::class, [
                'label' => 'Numéro TVA',
                'attr' => ['placeholder' => 'Numéro de TVA'],
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('averagePrice', IntegerType::class, [
                'label' => 'Prix moyen',
                'attr' => ['placeholder' => 'Prix moyen'],
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-warning']
            ])
            ->add('latitude', HiddenType::class, [
                'label' => false,
            ])
            ->add('longitude', HiddenType::class, [
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
