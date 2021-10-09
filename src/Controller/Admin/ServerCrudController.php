<?php

namespace App\Controller\Admin;

use App\Admin\Field\JsonField;
use App\Entity\Server;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ServerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Server::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setEntityLabelInSingular('Server')
            ->setEntityLabelInPlural('Servers')
            ->setSearchFields(['id', 'name', 'data', 'search'])
            ->setPaginatorPageSize(100);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable('delete')
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('enabled')
            ->add('name');
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name')
            ->setTemplatePath('easy_admin/Server/name.html.twig')
        ;
        $enabled = Field::new('enabled');
        $data = JsonField::new('data')
            ->setTemplatePath('easy_admin/Server/data.html.twig')
        ;
        $websites = AssociationField::new('websites')
            ->setTemplatePath('easy_admin/Server/websites.html.twig')
        ;
        $updatedAt = DateTimeField::new('updatedAt');
        $createdAt = DateTimeField::new('createdAt');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$name, $data, $websites, $enabled, $updatedAt, $createdAt];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$name, $enabled, $data, $websites];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$name, $enabled];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$name, $enabled];
        }
    }
}
