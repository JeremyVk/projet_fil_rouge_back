<?php

namespace App\Controller\Admin;

use App\Entity\Articles\Book\Book;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class BookCrudController extends AbstractCrudController
{
    public function __construct(
        private BookVariantCrudController $bookVariantCrudController,
        private AdminContextProvider $adminContextProvider
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
        ;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('resume'),
            AssociationField::new('variants'),
            TextField::new('editor'),
            ImageField::new('image')->setBasePath('/images/products')->setUploadDir('public/images/products')->setRequired(false)->setUploadedFileNamePattern('[contenthash].[extension]'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $createVariant = Action::new('BookVariantCrudController', 'Ajouter un variant')
                        ->linkToUrl(function(Book $book) {
                            return $this->getCreateVariantUrl() . '&parent=' . $book->getId();
                        })
        ;
            return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->add(Crud::PAGE_DETAIL, $createVariant)
        ;
    }

    public function getCreateVariantUrl()
    {
        return $this->container->get(AdminUrlGenerator::class)
        ->setController(BookVariantCrudController::class)
        ->setAction(Action::NEW)
        ->generateUrl();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Livres')
            ->setEntityLabelInPlural('LIvre')
        ;
    }
    
}
