<?php

namespace App\Form;

use App\DBAL\Types\MealTimeType;
use App\Entity\Event;
use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{ChoiceType, SubmitType, TextType, DateType, TimeType, IntegerType};
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewEventType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('restaurant', EntityType::class, options: [
                'class' => Restaurant::class,
                'query_builder' => function (RestaurantRepository $rr) {
                    return $rr->createQueryBuilder('r')
                        ->select('r')
                        ->where('r.restorer = :restorer')
                        ->setParameter('restorer', $this->security->getUser());
                },
                'choice_label' => 'name',
                "label" => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un restaurant ou en créer un',
                    ])
                ]])

            ->add('date', DateType::class, [
                'widget' => 'single_text',
                "label" => false,
                'html5' => true,
                'required' => true,
                'placeholder' => 'Date',
            ])
            ->add('time', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => '00:00'],
                'required' => true,
            ])
            ->add('meal', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Déjeuner/Dîner',
                'choices' => MealTimeType::getChoices(),
                'required' => true,
            ])
            ->add('places', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => ['placeholder' => 'Nombre de places'],
                'required' => true,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('theme', TextType::class, [
                'label' => 'Thème (facultatif)',
                'attr' => ['placeholder' => 'Thème (facultatif)'],
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-warning']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
