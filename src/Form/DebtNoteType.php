<?php

namespace Pos\Form;

use Doctrine\ORM\EntityRepository;
use Pos\Entity\Client;
use Pos\Entity\Sale;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DebtNoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'client',
                EntityType::class,
                [
                    'class' => Client::class,
                    'choice_label' => 'name',
                    'label' => 'Kunde',
                    'placeholder' => 'Kunde auswählen',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->leftjoin('Pos:Sale', 'n', 'with', 'n.client = c.id')
                            ->andWhere('n.id is null');
                    },

                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sale::class,
        ]);
    }
}
