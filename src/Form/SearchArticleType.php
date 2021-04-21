<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchArticleType extends AbstractType
{

    private $categories;

    public  function __construct(CategoryRepository $categoriesRepo)
    {
        $this->categories = $categoriesRepo->findAll();
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mots', SearchType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un ou plusieurs mots-clés'
                ],
                'required' => false
            ])
            ->add('categorie', ChoiceType::class, [
                'placeholder' => 'choisir une categorie',
                'choices' => $this->categories,
                'choice_value' => 'name',

                'choice_label' => function(?Category $category) {
                    return $category ? strtoupper($category->getName()) : '';
                },

                'choice_attr' => function(?Category $category) {
                    return $category ? ['class' => 'category_'.strtolower($category->getName())] : [];
                },
            ])
            ->add('Rechercher', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
