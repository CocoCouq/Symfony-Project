<?php

namespace App\Form;

use App\Entity\GamesList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('validate')
            ->add('materiel')
            ->add('categories',ChoiceType::class, [
                'choices' => $this->getChoices()
                ])
            ->add('rulesDetails', TextareaType::class)
            ->add('rulesDescription')
            ->add('rulesUrl')
            ->add('rulesWin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GamesList::class,
            'translation_domain' => 'forms'
        ]);
    }

    private function getChoices()
    {
        $choices = GamesList::CATEG;
        $output = [];

        foreach ($choices as $key => $value)
        {
            $output[$value] = $key;
        }
        return $output;
    }
}
