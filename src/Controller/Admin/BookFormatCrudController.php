<?php

namespace App\Controller\Admin;

use App\Entity\Formats\BookFormat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BookFormatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BookFormat::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
