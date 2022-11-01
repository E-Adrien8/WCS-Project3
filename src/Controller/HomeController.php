<?php

namespace App\Controller;

use App\Event\Model\EventSearch;
use App\Form\EventSearchType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EventRepository $eventRepository): Response
    {
        $search = new EventSearch();

        $form = $this->createForm(EventSearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $events = $eventRepository->search($search);
        }

        $display = $eventRepository->display();

        return $this->renderForm('home/index.html.twig', [
            'form' => $form,
            'events' => $events ?? null,
            'display' => $display,
        ]);
    }

    public function searchForm(Request $request): Response
    {
        $search = new EventSearch();

        $form = $this->createForm(EventSearchType::class, $search);
        $form->handleRequest($request);

        return $this->renderForm('common/form_search.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/concept', name: 'app_concept')]
    public function concept(): Response
    {
        return $this->render('home/concept.html.twig');
    }

    #[Route('/restaurateur', name: 'app_restorer')]
    public function restorer(): Response
    {
        return $this->render('home/restorer.html.twig');
    }

    #[Route('/mentions-legales', name: 'app_legale_notice')]
    public function legalNotice(): Response
    {
        return $this->render('home/legale_notice.html.twig');
    }

    #[Route('/charte', name: 'app_charter')]
    public function charter(): Response
    {
        return $this->renderForm('home/charter.html.twig');
    }

    #[Route('/press', name: 'app_press')]
    public function press(): Response
    {
        return $this->renderForm('home/press.html.twig');
    }

    #[Route('/faq', name: 'app_faq')]
    public function faq(): Response
    {
        return $this->renderForm('home/faq.html.twig');
    }
}
