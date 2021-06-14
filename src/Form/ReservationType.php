<?php

namespace App\Form;






use App\Entity\Reservation;

use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Part\TextPart;
use Symfony\Component\OptionsResolver\OptionsResolver;
class ReservationType extends AbstractType
{
    private $transformer;
    //injection dependance
    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer=$transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_debut',TextType::class,['attr' => [
                    'size'=>'3',
                    'class'=>'col-5'
                ]])

            ->add('date_fin',TextType::class,['attr' => [
        'size'=>'3',
        'class'=>'col-5'
            ]])
                ->add('motif', TextType::class,[
                    'attr' => ['placeholder' => 'Motif de la reservation',
                        'size'=>'3',
                        'class'=>'col-5'
                    ]
                ])
                ->add('description', TextareaType::class,[
                    'required'=> false,
                    'attr' => ['placeholder' => '(facultatif)'

                    ]
                ])
                ->add('confirmer', SubmitType::class, [
                   'attr' => ['label' => 'Confirmer']
                ]);

        $builder->get('date_debut')->addModelTransformer($this->transformer);
        $builder->get('date_fin')->addModelTransformer($this->transformer);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
