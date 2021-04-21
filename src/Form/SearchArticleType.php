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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

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
            ->setMethod("get")
            ->add('mots', SearchType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrez un ou plusieurs mots-clés'
                ],
                'required' => false
            ])
            ->add('order', CheckboxType::class, [
                'label'    => 'Plus encien ? ',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input mx-2'
                ],
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
            ->add('rechercher', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-3',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => "get",
            'crsf_protection' => false
        ]);
    }
}
