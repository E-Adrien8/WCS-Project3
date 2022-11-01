<?php

namespace App\Controller;

use App\Entity\ResetPasswordRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;

/**
 * @method User|null getUser()
 */
class SecurityController extends AbstractController
{
    #[Route(path: '/user/login', name: 'app_user_login')]
    public function loginUser(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login_user.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: 'user/logout', name: 'app_logout_user')]
    public function logout(): void
    {
    }
}
