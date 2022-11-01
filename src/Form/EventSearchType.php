<?php

namespace App\Form;

use App\DBAL\Types\MealTimeType;
use App\Entity\FoodType;
use App\Entity\Zone;
use App\Event\Model\EventSearch;
use App\Repository\FoodTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{ChoiceType, TextType, DateType};

class EventSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => false,
                'html5' => true,
                'required' => false,
            ])
            ->add('meal', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Déjeuner/Dîner',
                'choices' => MealTimeType::getChoices(),
                'required' => false,
            ])
            ->add('type', EntityType::class, [
                'class' => FoodType::class,
                'query_builder' => function (FoodTypeRepository $foodTypeRepository) {
                    return $foodTypeRepository->createQueryBuilder('f')
                        ->orderBy('f.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Type de cuisine',
                'required' => false,
            ])
            ->add('restaurant', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Nom du restaurant'],
                'required' => false,
            ])
            ->add('zone', EntityType::class, [
                'class' => Zone::class,
                'choice_label' => 'city',
                'label' => false,
                'placeholder' => 'Secteur géographique',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventSearch::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'action' => '/',
        ]);
    }
}
