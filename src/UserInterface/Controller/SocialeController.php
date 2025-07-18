<?php

namespace RightSide\Controller;

use Google\Client;
use Google\Service\Drive;
use League\OAuth2\Client\Provider\Google;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;  
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SocialeController extends AbstractController
{

    public function __invoke(Request $request, ClientRegistry $clientRegistry): Response
    {
        return $this->render('sociale/index.html.twig', [
            'controller_name' => 'SocialeController',
        ]);
    }

    // public function connectAction(ClientRegistry $clientRegistry)
    // {
    //     // will redirect to Facebook!
    //     return $clientRegistry
    //         ->getClient('google') // key used in config/packages/knpu_oauth2_client.yaml
    //         ->redirect([
    //             'public_profile', 'email' // the scopes you want to access
    //         ]);
    // }
//    private $router;

//     public function __construct(RouterInterface $router, ClientRegistry $clientRegistry)
//     {
//         $this->router = $router;
//         $this->clientRegistry = $clientRegistry;
//     }

//     public function supports(Request $request): ?bool
//     {
//         // Check if the request is for the Google login route
//         return $request->attributes->get('_route') === 'google_connect';
//     }

//     public function getCredentials(Request $request): array
//     {
//         // No credentials needed for this route, as it will redirect to Google
//         return $this->fetchAccessToken($this->getOAuth2Client());
//     }

//     public function getOAuth2Client(): OAuth2ClientInterface
//     {
//         // Get the Google client from the ClientRegistry
//         return $this->clientRegistry->getClient('google');
//     }

//     public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
//     {
//         // Redirect to the homepage or any other route after successful authentication
//         return new RedirectResponse($this->router->generate('cv_elhadi'));
//     }

//     public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
//     {
//         // Handle authentication failure (e.g., redirect to login with error message)
//         return new RedirectResponse($this->router->generate('google_login'));
//     }

//     /**
//      * @param League\OAuth2\Client\Token\AccessToken $credentials
//      */
//     public function __invoke(Request $credentials, UserProviderInterface $userProvider): ?UserInterface
//     {
//         // This method is not needed for the Google login flow
//         dd($credentials);
//     }
}