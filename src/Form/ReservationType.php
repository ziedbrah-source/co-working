<?php

namespace App\Form;






use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Part\TextPart;
use Symfony\Component\OptionsResolver\OptionsResolver;
class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_debut')
            ->add('date_fin')

            ->add('motif', TextType::class,[
                'attr' => ['placeholder' => 'Motif de la reservation',
                    'size'=>'3',
                    'class'=>'col-5'
                ]
            ])
            ->add('description', TextareaType::class,[
                'attr' => ['placeholder' => 'Description courte'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
