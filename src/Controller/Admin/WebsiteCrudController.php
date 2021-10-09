<?php

namespace App\Controller\Admin;

use App\Admin\Field\JsonField;
use App\Controller\Admin\Filter\WebsiteTypeFilter;
use App\Entity\Website;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class WebsiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Website::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setEntityLabelInSingular('Website')
            ->setEntityLabelInPlural('Websites')
            ->setSearchFields(['id', 'domain', 'documentRoot', 'type', 'version', 'data', 'comments', 'errors', 'updates', 'siteRoot', 'search'])
            ->setPaginatorPageSize(300);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable('new', 'delete')
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(WebsiteTypeFilter::new('type'))
            ->add(EntityFilter::new('server'))
            ->add(EntityFilter::new('audiences'));
    }

    public function configureFields(string $pageName): iterable
    {
        $domain = TextField::new('domain')
            ->setTemplatePath('easy_admin/Website/domain.html.twig')
            ->addCssClass('domain')
        ;
        $documentRoot = TextField::new('documentRoot');
        $type = TextField::new('type')
            ->setTemplatePath('easy_admin/Website/list/filter.html.twig')
        ;
        $version = TextField::new('version');
        $comments = TextareaField::new('comments')
            ->setTemplatePath('easy_admin/Website/list/comments.html.twig')
        ;
        $errors = TextareaField::new('errors');
        $updates = TextareaField::new('updates');
        $siteRoot = TextField::new('siteRoot');
        $enabled = Field::new('enabled');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');
        $server = AssociationField::new('server');
        $audiences = AssociationField::new('audiences')
            ->setTemplatePath('easy_admin/Website/list/filter.html.twig')
        ;
        $git = TextareaField::new('git')
            ->setTemplatePath('easy_admin/Website/data/git.html.twig')
        ;
        $data = JsonField::new('data')
            ->setTemplatePath('easy_admin/Website/data.html.twig')
        ;

        if (Crud::PAGE_INDEX === $pageName) {
            return [$domain, $server, $type, $version, $audiences, $comments, $data, $updatedAt];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$domain, $server, $documentRoot, $type, $version, $audiences, $git, $data, $updatedAt];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$domain, $documentRoot, $type, $version, $comments, $errors, $updates, $siteRoot, $enabled, $createdAt, $updatedAt, $server, $audiences];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$domain, $comments, $audiences];
        }
    }
}
