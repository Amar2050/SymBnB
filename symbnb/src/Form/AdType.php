<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{

    /**
     * Give basic field config 
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration ($label, $placeholder, $options = [] ) {
        return array_merge([
            'label'         => $label ,
            'attr'           => [
                'placeholder'   => $placeholder 
            ]
        ], $options);

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class , 
            $this->getConfiguration("Titre", "Renseignez un titre pour votre annonce") )

            ->add('slug' , TextType::class ,
            $this->getConfiguration("Adresse web", "Renseignez une adresse web (automatique)", [
                'required' => false ] ) )

            ->add('coverImage', UrlType::class,
            $this->getConfiguration("URL de l'image principale", "Envoyez votre meilleur image !") )
            
            ->add('introduction', TextType::class,
            $this->getConfiguration("Introduction", "Tapez une introduction pour votre annonce") )
            
            ->add('content', TextareaType::class,
            $this->getConfiguration("Déscriptif", "Donnez nous envie de venir chez vous !") )
            
            ->add('price', MoneyType::class,
            $this->getConfiguration("Prix par nuit", "Renseignez un prix par nuit") )
            
            ->add('rooms', IntegerType::class,
            $this->getConfiguration("Chambres", "Renseignez votre nombre de chambres") )
             
            ->add('images', CollectionType::class, 
            [
                'entry_type'    => ImageType::class,
                'allow_add'     => true,
                'allow_delete'  =>true
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
