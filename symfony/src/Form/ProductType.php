<?php

namespace Pos\Form;

use Pos\Entity\Category;
use Pos\Entity\Product;
use Pos\Form\Transformer\NumberToLocalizedTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
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
                'name',
                TextType::class
            )
            ->add(
                'price',
                TextType::class
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'required' => true,
                    'class' => Category::class,
                    'choice_label' => 'name',
                ]

            );;

        $builder->get('price')->addModelTransformer($this->numberTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
