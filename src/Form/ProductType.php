<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Product name',
                    'class' => 'form-control',
                ]
            ])
            ->add('slug', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Product description',
                    'class' => 'form-control',
                ]
            ])
            ->add('price', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Product price',
                    'class' => 'form-control',
                ]
            ])
            ->add('img', FileType::class, [
                'mapped'=> false,
                'label' => 'Please select images',
                //'multiple' - выбор нескольких картинок в
                //окне выбора файла. обычно оно ненужно...
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control-file',
                ]
            ])
            ->add('Save product', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary py-3 px-5 mt-5',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
