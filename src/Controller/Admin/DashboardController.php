<?php

namespace App\Controller\Admin;

use App\Entity\Author\Author;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\Articles\Book\Book;
use App\Entity\Formats\BookFormat;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\Variants\BookVariant;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
         $url = $routeBuilder->setController(BookCrudController::class)->generateUrl();

        return $this->redirect($url);
//         $routeBuilder = $this->container->get(AdminUrlGenerator::class);
// +        $url = $routeBuilder->setController(BaseArticleCrudController::class)->generateUrl();
// +
// +        return $this->redirect($url);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('L2I');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'homepage');
        yield MenuItem::subMenu('Articles')->setSubItems([
            MenuItem::section('Livres'),
            MenuItem::linkToCrud('Livres', '', Book::class),
            MenuItem::linkToCrud('Variants de livres', '', BookVariant::class),
            MenuItem::linkToCrud('Formats de livres', '', BookFormat::class),
            MenuItem::linkToCrud('Auteurs', '', Author::class),
        ]);

        // yield MenuItem::linkToCrud('Livres', '', Book::class);
        // yield MenuItem::linkToCrud('Variants de livres', '', BookVariant::class);
        // yield MenuItem::linkToCrud('Formats de livres', '', BookFormat::class);

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', '', User::class);

        yield MenuItem::section('Commandes');
        yield MenuItem::linkToCrud('Commandes', '', Order::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
