<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\FoodType;
use App\Entity\Restaurant;
use App\Entity\Restorer;
use App\Entity\User;
use App\Entity\Zone;
use App\Repository\CommentRepository;
use App\Repository\RestaurantRepository;
use App\Repository\RestorerRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private UserRepository $userRepository;
    private RestorerRepository $restorerRepository;
    private RestaurantRepository $restaurantRepository;


    public function __construct(
        UserRepository $userRepository,
        RestorerRepository $restorerRepository,
        RestaurantRepository $restaurantRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->restorerRepository = $restorerRepository;
        $this->restaurantRepository = $restaurantRepository;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // USERS
        $nbUsers = $this->userRepository->count([]);
        /*$nbUsersVerified = $this->userRepository->count([
            'isVerified' => true,
        ]);*/
        // RESTORERS
        $nbRestorers = $this->restorerRepository->count([]);
        $nbRestorersVerified = $this->restorerRepository->count([
            'isVerified' => true,
        ]);

        $restorersNotVerified = $this->restorerRepository->findBy([
            'isVerified' => false,
        ]);

        //RESTAURANTS
        $nbRestaurant = $this->restaurantRepository->count([]);

        return $this->render('admin/dashboard.html.twig', [
            'nbUsers' => $nbUsers,
            /*'nbUsersVerified' => $nbUsersVerified,*/
            'nbRestorer' => $nbRestorers,
            'nbRestorersVerified' => $nbRestorersVerified,
            'restorersNotVerified' => $restorersNotVerified,
            'nbRestaurant' => $nbRestaurant,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Copains De Resto');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Restaurateurs', 'fa fa-users', Restorer::class);
        yield MenuItem::linkToCrud('Restaurants', 'fa fa-cutlery', Restaurant::class);
        yield MenuItem::linkToCrud('Évènement', 'fa fa-calendar-check-o', Event::class);
        yield MenuItem::linkToCrud('Type de repas', 'fa fa-list', FoodType::class);
        yield MenuItem::linkToCrud('Zones', 'fa fa-map-marker', Zone::class);
        yield MenuItem::linkToCrud('Commentaires', 'fa fa-commenting', Comment::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
