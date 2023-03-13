<?php

namespace App\Controller\Admin;

use App\Repository\BookRepository;
use App\Entity\Variants\BookVariant;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

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
            NumberField::new("stock"),
            NumberField::new("isbnNumber"),
            MoneyField::new("unitPrice")->setCurrency('EUR')
        ];

        if ($parentId) {
            array_unshift($fields, AssociationField::new('parent')->setFormTypeOption('disabled', true));
        } else {
            array_unshift($fields, AssociationField::new('parent'));
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

    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('Détail', 'Détails du variant')->linkToCrudAction('read');
            return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->add(Crud::PAGE_DETAIL, $show)
        ;
    }
}
