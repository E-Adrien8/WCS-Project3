<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Restorer;
use App\Form\RestaurantAddType;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @method Restorer|null getUser()
 */

class RestaurantController extends AbstractController
{
    #[Route('/restorer/restaurants', name: 'app_restorer_restaurants')]
    public function restaurantsByRestorerId(RestaurantRepository $restaurantRepository): Response
    {
        $restaurants = $restaurantRepository->findBy([
            'restorer' => $this->getUser()
        ]);

        return $this->render('restaurant/restaurants.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }

    #[Route('/restorer/restaurant/{id<\d+>}', name: 'app_restaurant_show', methods: ['GET'])]
    public function showRestaurant(RestaurantRepository $restaurantRepository, Restaurant $restaurant): Response
    {
        $restaurant = $restaurantRepository->find($restaurant->getId());

        return $this->render('restaurant/restaurant_show.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('/restorer/restaurant/{id<\d+>}/modifier', name: 'app_restaurant_update')]
    public function updateRestaurant(
        Request $request,
        RestaurantRepository $restaurantRepository,
        Restaurant $restaurant,
        SluggerInterface $slugger
    ): Response {
        $form = $this->createForm(RestaurantAddType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restaurantRepository->add($restaurant, true);

            $menuFile = $form->get('menuPdf')->getData();
            $mainPicture = $form->get('mainPicture')->getData();

            if ($menuFile) {
                $originalFilename = pathinfo($menuFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $menuFile->guessExtension();

                try {
                    $menuFile->move(
                        $this->getParameter('menuPdf_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo 'Exception reçue : ', $e->getMessage(), "\n";
                }

                $restaurant->setMenuPdf($newFilename);
            }

            if ($mainPicture) {
                $originalPictureName = pathinfo($mainPicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safePictureName = $slugger->slug($originalPictureName);
                $newPictureName = $safePictureName . '-' . uniqid() . '.' . $mainPicture->guessExtension();

                try {
                    $mainPicture->move(
                        $this->getParameter('mainPicture_directory'),
                        $newPictureName
                    );
                } catch (FileException $e) {
                    echo 'Exception reçue : ', $e->getMessage(), "\n";
                }

                $restaurant->setMainPicture($newPictureName);
            }

            $restaurantRepository->add($restaurant, true);


            $this->addFlash('success', 'Informations modifiées');

            return $this->redirectToRoute('app_restaurant_show', [
                'id' => $restaurant->getId(),
            ]);
        }

        return $this->renderForm('restaurant/restaurant_update.html.twig', [
            'restaurant' => $restaurant,
            'updateForm' => $form,
        ]);
    }

    #[Route('/restorer/restaurant/ajouter', name: 'app_restaurant_new')]
    public function newRestaurant(
        Request $request,
        RestaurantRepository $restaurantRepository,
        SluggerInterface $slugger
    ): Response {

        $newRestaurant = new Restaurant();

        $form = $this->createForm(RestaurantAddType::class, $newRestaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menuFile = $form->get('menuPdf')->getData();
            $mainPicture = $form->get('mainPicture')->getData();

            if ($menuFile) {
                $originalFilename = pathinfo($menuFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $menuFile->guessExtension();

                try {
                    $menuFile->move(
                        $this->getParameter('menuPdf_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo 'Exception reçue : ', $e->getMessage(), "\n";
                }

                $newRestaurant->setMenuPdf($newFilename);
            }

            if ($mainPicture) {
                $originalPictureName = pathinfo($mainPicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safePictureName = $slugger->slug($originalPictureName);
                $newPictureName = $safePictureName . '-' . uniqid() . '.' . $mainPicture->guessExtension();

                try {
                    $mainPicture->move(
                        $this->getParameter('mainPicture_directory'),
                        $newPictureName
                    );
                } catch (FileException $e) {
                    echo 'Exception reçue : ', $e->getMessage(), "\n";
                }

                $newRestaurant->setMainPicture($newPictureName);
            }

            $newRestaurant->setRestorer($this->getUser());
            $restaurantRepository->add($newRestaurant, true);
            $this->addFlash('success', 'Restaurant ajouté');

            return $this->redirectToRoute('app_restaurant_show', [
                'id' => $newRestaurant->getId(),
            ]);
        }

        return $this->renderForm('restaurant/restaurant_add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/restorer/restaurant{id<\d+>}/supprimer', name: 'app_restaurant_delete', methods: ['GET'])]
    public function deleteRestaurant(RestaurantRepository $restaurantRepository, Restaurant $restaurant): Response
    {
        $restaurantRepository->remove($restaurant, true);
        $this->addFlash('success', 'Le restaurant a été supprimé');

        return $this->redirectToRoute('app_restorer_restaurants');
    }
}
