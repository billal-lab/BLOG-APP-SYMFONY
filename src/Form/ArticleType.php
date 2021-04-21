<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ArticleType extends AbstractType
{

    private $categories;

    public  function __construct(CategoryRepository $categoriesRepo)
    {
        $this->categories = $categoriesRepo->findAll();
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('title')
            ->add('categorie', ChoiceType::class, [
                'choices' => $this->categories,
                'choice_value' => 'name',

                'choice_label' => function(?Category $category) {
                    return $category ? strtoupper($category->getName()) : '';
                },

                'choice_attr' => function(?Category $category) {
                    return $category ? ['class' => 'category_'.strtolower($category->getName())] : [];
                },
            ])
            ->add('image', FileType::class, [
                'required' => true,
                'label' => 'Image (png, jpeg or jpg)',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
