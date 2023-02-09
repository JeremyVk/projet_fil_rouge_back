<?php

namespace App\Controller\Admin;

use App\Entity\Variants\BookVariant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use App\Repository\BookRepository;

class BookVariantCrudController extends AbstractCrudController
{
    public function __construct(
        private BookRepository $bookRepository
    )
    {
        
    }
    public static function getEntityFqcn(): string
    {
        return BookVariant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $parentId = isset($_GET['parent']);
        $fields = [
            AssociationField::new('format'),
        ];

        if ($parentId) {
            $fields [] = AssociationField::new('parent')
            ->setDisabled('disabled','true')
            ;
        } else {
            $fields [] = AssociationField::new('parent');
        }

        return $fields;
    }

    public function createEntity(string $entityFqcn)
    {
        $parentId = isset($_GET['parent']);

        $bookVariant = new BookVariant();
        if ($parentId) {
            $parent = $this->bookRepository->find($_GET['parent']);
            $bookVariant->setParent($parent);
        }

        return $bookVariant;
    }
}
