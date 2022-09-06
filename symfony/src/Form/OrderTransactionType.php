<?php

namespace Pos\Form;

use Pos\Entity\Transaction;
use Pos\Form\Transformer\NumberToLocalizedTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderTransactionType extends AbstractType
{
    protected NumberToLocalizedTransformer $numberTransformer;

    public function __construct(NumberToLocalizedTransformer $numberTransformer)
    {
        $this->numberTransformer = $numberTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'date',
                DateType::class,
                [
                    'widget' => 'choice',
                    'format' => 'dd.MM.yyyy',
                ]
            )
            ->add(
                'item',
                TextType::class
            )
            ->add(
                'price',
                TextType::class
            )
            ->add(
                'type',
                HiddenType::class,
                [
                    'data' => Transaction::TYPE_ORDER,
                ]
            );

        $builder->get('price')->addModelTransformer($this->numberTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
