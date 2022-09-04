<?php

namespace Pos\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PrintType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'from',
                DateType::class,
                [
                    'widget' => 'choice',
                    'placeholder' => [
                        'month' => 'Monat',
                    ],
                ]
            )
            ->add(
                'to',
                DateType::class,
                [
                    'widget' => 'choice',
                    'placeholder' => [
                        'month' => 'Monat',
                    ],
                ]
            )
            ->add(
                'startBalance',
                NumberType::class
            );
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
