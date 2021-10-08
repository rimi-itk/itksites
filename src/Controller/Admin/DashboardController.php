<?php

namespace App\Controller\Admin;

use App\Entity\Server;
use App\Entity\Website;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ITK sites');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Website', 'fas fa-list', Website::class);
        yield MenuItem::linkToCrud('Server', 'fas fa-list', Server::class);
    }
}
