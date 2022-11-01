<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\EventRepository;

/**
 * @method User|null getUser()
 */

class MyReservationController extends AbstractController
{
    #[Route('/user/reservations', name: 'app_my_reservation')]
    public function findMyReservation(EventRepository $eventRepository): Response
    {
        return $this->render('reservation/reservation.html.twig', [
            'futurEvents' => $eventRepository->getNextEventAsAttendee($this->getUser()),
            'pastEvents' => $eventRepository->getPastEventAsAttendee($this->getUser()),
        ]);
    }
}
