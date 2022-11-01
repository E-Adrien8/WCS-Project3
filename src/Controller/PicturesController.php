<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Restaurant;
use App\Form\PicturesType;
use App\Repository\PictureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PicturesController extends AbstractController
{
    #[Route('/restorer/restaurant/photos/{id<\d+>}', name: 'app_restaurant_pictures')]
    public function pictures(Request $request, PictureRepository $pictureRepository, SluggerInterface $slugger, Restaurant $restaurant): Response
    {
        $pictures = $restaurant->getPictures();

        $newPicture = new Picture();

        $form = $this->createForm(PicturesType::class, $newPicture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $originalPictureName = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safePictureName = $slugger->slug($originalPictureName);
                $newPictureName = $safePictureName . '-' . uniqid() . '.' . $picture->guessExtension();

                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $newPictureName
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Un problème est survenu avec le téléchargement de votre
                    photo, veuillez réessayer');
                }

                $newPicture->setPicture($newPictureName);
            }

            $newPicture->setRestaurant($restaurant);
            $pictureRepository->add($newPicture, true);
            $this->addFlash('success', 'Photo ajoutée');

            return $this->redirectToRoute('app_restaurant_pictures', [
                'id' => $newPicture->getRestaurant()->getId(),
            ]);
        }

        return $this->renderForm('pictures/pictures.html.twig', [
            'form' => $form,
            'pictures' => $pictures,
        ]);
    }

    #[Route('/restorer/restaurant/supprimer/{id<\d+>}', name: 'app_restaurant_delete_pictures', methods: ['GET'])]
    public function deletePictures(PictureRepository $pictureRepository, Picture $picture): Response
    {
        $pictureRepository->remove($picture, true);
        $this->addFlash('success', 'La photo a été supprimée');

        return $this->redirectToRoute('app_restaurant_pictures', [
            'id' => $picture->getRestaurant()->getId(),
        ]);
    }
}
