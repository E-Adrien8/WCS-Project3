<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Form\CommentAddType;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use App\Entity\User;
use App\Security\EventReservationVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method User|null getUser()
 */
class EventController extends AbstractController
{
    #[Route('/event/{id<\d+>}', name: 'app_event')]
    public function show(
        Event $event,
        EventRepository $eventRepository,
        CommentRepository $commentRepository,
        Request $request,
    ): Response {
        if ($request->query->has('version')) {
            $version = $request->query->get('version') === 'mobile' ? 'mobile' : 'desktop';
            $request->getSession()->set('version', $version);
        }

        if (!$request->getSession()->has('version')) {
            $request->getSession()->set('version', 'desktop');
        }

        $nextEvents = $eventRepository->nextDateEvent($event);

        if ($this->getUser()) {
            $comment = new Comment();
            $comment->setUser($this->getUser());
            $comment->setRestaurant($event->getRestaurant());

            $form = $this->createForm(CommentAddType::class, $comment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $commentRepository->add($comment, true);
                $this->addFlash('success', 'Votre avis a été posté !');
                return $this->redirectToRoute('app_event', ['id' => $event->getId(),
                ]);
            }
        }

        $page = (int)$request->query->get('p', '1');
        $comments = $commentRepository->getCommentsPaginator($event->getRestaurant(), $page, 6);
        $totalComments = count($comments);
        $nbPages = ceil($totalComments / 6);

        $template = $request->getSession()->get('version') === 'mobile' ? 'show_mobile' : 'show_desktop';

        return $this->renderForm('event/' . $template . '.html.twig', [
            'event' => $event,
            'nextEvents' => $nextEvents,
            'comments' => $comments,
            'nbPages' => $nbPages,
            'CommentAddForm' => $form ?? null,
        ]);
    }

    #[Route('/event/{id<\d+>}/reservation', name: 'app_event_reservation', methods: ['GET'])]
    #[IsGranted(EventReservationVoter::RESERVATION, subject: 'event')]
    public function reservation(Event $event, EntityManagerInterface $entityManager, MailerInterface $mailer): null|Response
    {
        if ($event->getAttendees()->contains($this->getUser())) {
            return $this->redirectToRoute('app_home');
        }

        $event->addAttendee($this->getUser());
        $entityManager->flush();

        //Envoie email à la personne inscrite pour évènement non confirmé
        $message = (new Email())
            ->from($this->getParameter('email_from'))
            ->to($this->getUser()->getEmail())
            ->subject('Vous êtes inscrit à l\'évènement à ' . $event->getRestaurant()->getName() . ' le ' . $event->getDate()->format('d-m-Y') . ' - Copains De resto')
            ->html($this->renderView('email/eventReservationEmail.html.twig', [
                'event' => $event,
                'user' => $this->getUser(),
            ]));
        $mailer->send($message);

        if ($event->getAttendees()->count() === 2) {
            //Envoie email à la première personne inscrite pour confirmation d'évènement
            $registeredUser = $event->getAttendees()->getValues();
            $message = (new Email())
                ->from($this->getParameter('email_from'))
                ->to($registeredUser[0]->getEmail())
                ->subject('Confirmation de l\'évènement à ' . $event->getRestaurant()->getName() . ' le ' . $event->getDate()->format('d-m-Y') . ' - Copains De resto')
                ->html($this->renderView('email/eventReservationEmail.html.twig', [
                    'event' => $event,
                    'user' => $registeredUser[0],
                ]));
            $mailer->send($message);
        }

        $this->addFlash('success', 'Vous êtes inscrit à cet évènement !');

        return $this->redirectToRoute('app_event', [
            'id' => $event->getId(),
        ]);
    }

    #[Route('/reservation', name: 'app_reservation')]
    public function index(EventRepository $eventRepository, Request $request): Response
    {
        $page = (int)$request->query->get('p', '1');
        $events = $eventRepository->getEventsPaginator($page, 6);
        $totalEvents = count($events);
        $nbPages = ceil($totalEvents / 6);

        return $this->render('event/index.html.twig', [
            'events' => iterator_to_array($events),
            'currentPage' => $page,
            'nbPages' => $nbPages,
        ]);
    }

    #[Route('/event/{id<\d+>}/delete', name: 'app_event_reservation_delete', methods: ['GET'])]
    public function deleteReservation(Event $event, EntityManagerInterface $entityManager, MailerInterface $mailer): null|Response
    {
        $event->removeAttendee($this->getUser());
        $entityManager->flush();
        if ($event->getAttendees()->count() < 2) {
            //Envoie email à la première personne inscrite pour confirmation d'évènement
            $registeredUser = $event->getAttendees()->getValues();
            $message = (new Email())
                ->from($this->getParameter('email_from'))
                ->to($registeredUser[0]->getEmail())
                ->subject('L\'évènement à ' . $event->getRestaurant()->getName() . ' le ' . $event->getDate()->format('d-m-Y') . ' n\'est pas confirmé - Copains De resto')
                ->html($this->renderView('email/eventReservationEmail.html.twig', [
                    'event' => $event,
                    'user' => $registeredUser[0],
                ]));
            $mailer->send($message);
        }
        $this->addFlash('success', 'Vous ne participez plus à cet évènement.');
        return $this->redirectToRoute('app_event', array('id' => $event->getId()));
    }
}
