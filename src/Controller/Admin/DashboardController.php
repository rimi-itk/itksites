<?php

namespace App\Controller\Admin;

use App\Entity\Audience;
use App\Entity\Server;
use App\Entity\Website;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(WebsiteCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ITK sites');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Website', 'fas fa-globe', Website::class);
        yield MenuItem::linkToCrud('Server', 'fas fa-server', Server::class);
        yield MenuItem::section('Misc');
        yield MenuItem::linkToCrud('Audience', 'fas fa-users', Audience::class);
        yield MenuItem::section('Export');
        $url = $this->generateUrl('api_websites_get_collection', [
            '_format' => 'csv',
            'pagination' => 'false',
            'enabled' => '1',
        ]);
        yield MenuItem::linkToUrl('Website (csv)', 'fas fa-file-csv', $url);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)->setMenuItems([
            // Remove the logout menu item.
        ]);
    }
}
