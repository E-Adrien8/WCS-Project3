<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\RegistrationFormType;
use App\Form\UpdateCompteFormType;
use App\Form\UpdateProfilFormType;
use App\Repository\UserRepository;
use App\Security\LoginUserFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
 * @method User|null getUser()
 */
class UserController extends AbstractController
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }

    #[Route('/user/register', name: 'app_user_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'app_verify_email',
                (string)$user->getId(),
                $user->getEmail(),
                ['id' => $user->getId()]
            );

            $email = new TemplatedEmail();
            $email->from('contact@copainsderesto.fr');
            $email->to($user->getEmail());
            $email->htmlTemplate('registration/confirmation_email.html.twig');
            $email->context([
                'signedUrl' => $signatureComponents->getSignedUrl(),
                'expiresAtMessageKey' => $signatureComponents->getExpirationMessageKey(),
                'expiresAtMessageData' => $signatureComponents->getExpirationMessageData(),
            ]);

            $this->mailer->send($email);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/user_register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        TranslatorInterface $translator,
        UserRepository $userRepository,
        UserAuthenticatorInterface $userAuthenticator,
        LoginUserFormAuthenticator $formAuthenticator
    ): Response {
        $id = $request->get('id');

        if (!$id) {
            throw $this->createNotFoundException();
        }

        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), (string)$user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans(
                $exception->getReason(),
                [],
                'VerifyEmailBundle'
            ));

            return $this->redirectToRoute('app_home');
        }

        $user->setIsVerified(true);
        $userRepository->add($user, true);

        $userAuthenticator->authenticateUser($user, $formAuthenticator, $request);

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_user_compte');
    }

    #[Route('/user/compte/', name: 'app_user_compte')]
    public function show(): Response
    {
        $user = $this->getUser();

//        $birthdate = $user->getBirthdate();
//        $formatBirthdate = date_format($birthdate, "Y-m-d");
//        $date = date("Y-m-d");
//        $age = date_diff(date_create($formatBirthdate), date_create($date));

        return $this->render('user/compte_user.html.twig', [
            'user' => $user,
//            'age' => $age,
        ]);
    }

    #[Route('/user/compte/update', name: 'app_user_compte_update')]
    public function update(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdateCompteFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);
            $this->addFlash('success', 'Compte modifié');

            return $this->redirectToRoute('app_user_compte');
        }

        return $this->render('user/compte_user_update.html.twig', [
            'user' => $user,
            'updateCompteForm' => $form->createView(),
        ]);
    }

    #[Route('/user/profil/{id<\d+>}', name: 'app_user_profil', methods: ['GET'])]
    public function profileShow(User $user): Response
    {
        $birthdate = $user->getBirthdate();
        $now = new DateTime('now');
        $age = $now->diff($birthdate, true)->y;

        return $this->render('user/profil_user.html.twig', [
            'user' => $user,
            'age' => $age
        ]);
    }

    #[Route('/user/compte/supprimer', name: 'app_user_compte_delete')]
    public function delete(UserRepository $userRepository, TokenStorageInterface $tokenStorage, Request $request): Response
    {
        $userRepository->remove($this->getUser(), true);
        $response = $this->redirectToRoute('app_home');

        $tokenStorage->setToken(null);
        $request->getSession()->clear();
        $request->getSession()->invalidate();

        return $response;
    }

    #[Route('/user/compte/updateprofil', name: 'app_user_profil_update')]
    public function updateProfile(Request $request, UserRepository $userRepository, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdateProfilFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $originalPictureName = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safePictureName = $slugger->slug($originalPictureName);
                $newPictureName = $safePictureName . '-' . uniqid() . '.' . $picture->guessExtension();

                try {
                    $picture->move(
                        $this->getParameter('avatars_directory'),
                        $newPictureName
                    );
                } catch (FileException $e) {
                    echo 'Exception reçue : ', $e->getMessage(), "\n";
                }

                $user->setPicture($newPictureName);
            }

            $userRepository->add($user, true);

            $this->addFlash('success', 'Profil modifié');

            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()]);
        }

        return $this->render('user/profil_user_update.html.twig', [
            'user' => $user,
            'updateProfilForm' => $form->createView(),
        ]);
    }

    #[Route('/user/compte/updatepassword', name: 'app_user_password_update')]
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

            return $this->redirectToRoute('app_user_compte');
        }
        return $this->render('user/compte_password_update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
