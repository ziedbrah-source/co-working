<?php

namespace App\Form;

use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SalleCreationType extends AbstractType
{
    /**
     * Permet d'avoir la confiuration de base d'un champ!
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options =[]){
    return array_merge([
        'label'=> $label,
        'attr' => [
            'placeholder' => $placeholder
        ]
    ], $options);
}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, $this->getConfiguration("Nom de la salle","ex: Salle 157"))
            ->add('nbr_tables',IntegerType::class, $this->getConfiguration("Nombre de tables","ex: 5"))
            ->add('data_show', IntegerType::class, $this->getConfiguration("Data show","ex: 1 ou 0"))
            ->add('climatisation',IntegerType::class,$this->getConfiguration("Climatisation","ex: 1 ou 0"))
            ->add('nbr_tableaux',IntegerType::class,$this->getConfiguration("Nombre de tableaux","ex: 2"))
            ->add('prix',MoneyType::class,$this->getConfiguration("Prix de l'heure ","ex: 5$"))
            ->add('imageFile',VichImageType::class, ['required' => false,
                'allow_delete' => true,
                'delete_label' => '...',
                'download_label' => '...',
                'download_uri' => true,
                'image_uri' => true,
                //'imagine_pattern' => 'product_photo_320x240',
                'asset_helper' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
