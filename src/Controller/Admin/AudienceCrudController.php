<?php

namespace App\Controller\Admin;

use App\Entity\Audience;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AudienceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Audience::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Audience')
            ->setEntityLabelInPlural('Audience')
            ->setSearchFields(['id', 'name']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable('delete');
    }

    public function configureFields(string $pageName): iterable
    {
        $id = TextField::new('id', 'ID');
        $name = TextField::new('name');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$id, $name];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$id, $name];
        }
    }
}
