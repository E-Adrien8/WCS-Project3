<?php

namespace App\Controller;

use App\Entity\Restorer;
use App\Form\ChangePasswordFormType;
use App\Form\RestorerRegistrationFormType;
use App\Form\RestorerUpdateCompteFormType;
use App\Repository\RestorerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @method Restorer|null getUser()
 */

class RestorerController extends AbstractController
{
    #[Route('/restorer', name: 'app_restorer')]
    public function index(): Response
    {
        return $this->render('restorer/index.html.twig');
    }

//    private EmailVerifier $emailVerifier;
//
//    public function __construct(EmailVerifier $emailVerifier)
//    {
//        $this->emailVerifier = $emailVerifier;
//    }

    #[Route('/restorer/register', name: 'app_restorer_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $restorerPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $restorer = new Restorer();
        $form = $this->createForm(RestorerRegistrationFormType::class, $restorer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $restorer->setPassword(
                $restorerPasswordHasher->hashPassword(
                    $restorer,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($restorer);
            $entityManager->flush();

//            // generate a signed url and email it to the user
//            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
//                (new TemplatedEmail())
//                    ->from(new Address('copainsderesto@gmail.com', 'Copains de Resto'))
//                    ->to($user->getEmail())
//                    ->subject('Please Confirm your Email')
//                    ->htmlTemplate('registration/confirmation_email.html.twig')
//            );
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_restorer_login');
        }

        return $this->render('registration/restorer_register.html.twig', [
            'restorerRegistrationForm' => $form->createView(),
        ]);
    }

    #[Route('/restorer/compte/', name: 'app_restorer_compte')]
    public function show(RestorerRepository $restorerRepository): Response
    {
        $restorer = $this->getUser();

        return $this->render('restorer/compte_restorer.html.twig', [
            'restorer' => $restorer,
        ]);
    }

    #[Route('/restorer/compte/update', name: 'app_restorer_compte_update')]
    public function update(Request $request, RestorerRepository $restorerRepository): Response
    {
        $restorer = $this->getUser();
        $form = $this->createForm(RestorerUpdateCompteFormType::class, $restorer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restorerRepository->add($restorer, true);
            $this->addFlash('success', 'Compte modifié');

            return $this->redirectToRoute('app_restorer_compte');
        }

        return $this->render('restorer/compte_restorer_update.html.twig', [
            'restorer' => $restorer,
            'restorerUpdateCompteForm' => $form->createView(),
        ]);
    }

    #[Route('/restorer/compte/supprimer', name: 'app_restorer_compte_delete')]
    public function delete(RestorerRepository $restorerRepository, TokenStorageInterface $tokenStorage, Request $request): Response
    {
        $restorerRepository->remove($this->getUser(), true);
        $response = $this->redirectToRoute('app_home');
        $response->headers->clearCookie('restorer');

        $tokenStorage->setToken(null);
        $request->getSession()->clear();
        $request->getSession()->invalidate();

        return $response;
    }

    #[Route('/restorer/compte/updatepassword', name: 'app_restorer_password_update')]
    public function updatePassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $this->getUser(),
                $plainPassword
            );
            $this->getUser()->setPassword($hashedPassword);

            $em->flush();

            $this->addFlash('success', 'Compte modifié');

            return $this->redirectToRoute('app_restorer_compte');
        }
        return $this->render('user/compte_password_update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

//    #[Route('/verify/email', name: 'app_verify_email')]
//    public function verifyUserEmail(Request $request, TranslatorInterface $translator,
// UserRepository $userRepository): Response
//    {
//        $id = $request->get('id');
//
//        if (null === $id) {
//            return $this->redirectToRoute('app_register');
//        }
//
//        $user = $userRepository->find($id);
//
//        if (null === $user) {
//            return $this->redirectToRoute('app_register');
//        }
//
//        // validate email confirmation link, sets User::isVerified=true and persists
//        try {
//            $this->emailVerifier->handleEmailConfirmation($request, $user);
//        } catch (VerifyEmailExceptionInterface $exception) {
//            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(),
// [], 'VerifyEmailBundle'));
//
//            return $this->redirectToRoute('app_register');
//        }
//
//        // @TODO Change the redirect on success and handle or remove the flash message in your templates
//        $this->addFlash('success', 'Your email address has been verified.');
//
//        return $this->redirectToRoute('app_register');
//    }
}
