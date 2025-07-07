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
        // include your composer dependencies

        $client = new Client();
        $client->setApplicationName($_ENV['GOOGLE_API_NAME']);
        $client->setDeveloperKey($_ENV['GOOGLE_API_KEY']);
        //dd('./../../Infrastructure/Data/client_secret_64541087361-d3pcqj5eppaadqe2c8dgi9046032t114.apps.googleusercontent.com.json');
        $client->setAuthConfig(__DIR__ . '/../../Infrastructure/Data/client_secret_64541087361-d3pcqj5eppaadqe2c8dgi9046032t114.apps.googleusercontent.com.json');
        // or use the path to your json file
        //$client->setAuthConfig($_ENV['GOOGLE_AUTH_CONFIG']);
        
        $client->addScope(Drive::DRIVE);
        
        $redirect_uri = 'http://127.0.0.1:8000'; // or the exact URL declared in Google
        $client->setRedirectUri($redirect_uri);
        
        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        }
        // // if you want to *authenticate* the user, then
        // //leave this method blank and create a Guard authenticator
        // //(read below)

         /** @var GoogleClient $client */
        $client = $clientRegistry->getClient('google');

        try {
            $provider = new Google([
                'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
                'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
                'redirectUri'  => 'http://127.0.0.1:8000', // or the exact URL declared in Google
                'hostedDomain' => 'http://127.0.0.1:8000', // optional; used to restrict access to users on your G Suite/Google Apps for Business accounts
            ]);
        // Gestion de l'état OAuth avec la session Symfony
        $state = $request->getSession()->get('oauth2state');
        if (!empty($_GET['error'])) {

            // Got an error, probably user denied access
            exit('Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));

        } elseif (empty($_GET['code'])) {

            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $request->getSession()->set('oauth2state', $provider->getState());
            return $this->redirect($authUrl);

        } elseif (empty($_GET['state']) || ($_GET['state'] !== $state)) {

            // State is invalid, possible CSRF attack in progress
            $request->getSession()->remove('oauth2state');
            exit('Invalid state');

        } else {

            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Optional: Now you have a token you can look up a users profile data
            try {

                // We got an access token, let's now get the owner details
                $ownerDetails = $provider->getResourceOwner($token);

                // Use these details to create a new profile
                printf('Hello %s!', $ownerDetails->getFirstName());

            } catch (\Exception $e) {

                // Failed to get user details
                exit('Something went wrong: ' . $e->getMessage());

            }

            // Use this to interact with an API on the users behalf
            echo $token->getToken();

            // Use this to get a new access token if the old one expires
            echo $token->getRefreshToken();

            // Unix timestamp at which the access token expires
            echo $token->getExpires();
        }
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\Google $user */
            $user = $client->fetchUser();

            // do something with all this new power!
            // e.g. $name = $user->getFirstName();
            var_dump($user->getFirstName()); die;
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage()); die;
        }
        if ($this->getUser()) {
            return $this->redirectToRoute('cv_elhadi'); // Redirect to a route after successful login
        }
        return $this->render('sociale/index.html.twig', [
            'client' => $client,
            'redirect_uri' => $redirect_uri,
            'google_client_id' => $_ENV['GOOGLE_CLIENT_ID'] ?? null,
            'google_client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'] ?? null,
            'google_api_name' => $_ENV['GOOGLE_API_NAME'] ?? null,
            'google_auth_config' => $_ENV['GOOGLE_AUTH_CONFIG'] ?? null,
        ]);
    }

    public function connectAction(ClientRegistry $clientRegistry)
    {
        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('google') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'public_profile', 'email' // the scopes you want to access
            ]);
    }
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