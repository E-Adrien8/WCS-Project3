<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Restorer;
use App\Form\NewEventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method Restorer|null getUser()
 */
class RestorerEventController extends AbstractController
{
    #[Route('/restorer/new-event', name: 'app_restorer_new_event')]
    public function newEvent(EventRepository $eventRepository, Request $request): Response
    {
        $newEvent = new Event();

        $form = $this->createForm(NewEventType::class, $newEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->add($newEvent, true);
            $this->addFlash('success', 'Évènement créé !');
            return $this->redirectToRoute('app_restorer_events');
        }
        return $this->renderForm('restorer/new-event.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/restorer/events/', name: 'app_restorer_events')]
    public function showAllEvents(EventRepository $eventRepository): Response
    {
        $response = $this->render('restorer/show-events.html.twig', [
            'futurEvents' => $eventRepository->getNextEventAsRestorer($this->getUser()),
            'pastEvents' => $eventRepository->getPastEventAsRestorer($this->getUser()),
        ]);

        $cookie = Cookie::create('restorer')->withValue('1');
        $response->headers->setCookie($cookie);

        return $response;
    }

//    #[Route('/restorer/event/{id<\d+>}', name: 'app_restorer_event', methods: ['GET'])]
//    public function showEvent(Event $event): Response
//    {
//        return $this->render('restorer/show-event.html.twig', [
//            'event' => $event,
//        ]);
//    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    #[Route('/restorer/event/supprimer/{id<\d+>}', name: 'app_restorer_event_delete', methods: ['GET'])]
    public function deleteEvent(EventRepository $eventRepository, Event $event, MailerInterface $mailer): Response
    {
        $users = $event->getAttendees()->getValues();
        for ($i = 0; $i < $event->getAttendees()->count(); $i++) {
            $user = $users[$i];
            $message = (new Email())
                ->from($this->getParameter('email_from'))
                ->to($users[$i]->getEmail())
                ->subject('Evènement annulé à ' . $event->getRestaurant()->getName() . ' le ' . $event->getDate()->format('d-m-Y') . ' - Copains De resto')
                ->html($this->renderView('email/emailReservationEmail.html.twig', [
                    'event' => $event,
                    'user' => $user,
                ]));
            $mailer->send($message);
        }
        $eventRepository->remove($event, true);
        $this->addFlash('success', 'Évènement supprimé');

        return $this->redirectToRoute('app_restorer_events');
    }

    #[Route('/restorer/event/modifier/{id<\d+>}', name: 'app_restorer_event_update')]
    public function updateEvent(EventRepository $eventRepository, Event $event, Request $request): Response
    {
        $form = $this->createForm(NewEventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->add($event, true);
            $this->addFlash('success', 'Évènement modifié');
            return $this->redirectToRoute('app_restorer_event', [
                'id' => $event->getId(),
            ]);
        }
        return $this->renderForm('restorer/update_event.html.twig', [
            'form' => $form,
        ]);
    }
}
