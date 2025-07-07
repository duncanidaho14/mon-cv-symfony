<?php

namespace RightSide\Controller;

use Google\Client;
use Google\Service\Books;
use League\OAuth2\Client\Grant\RefreshToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class GoogleController extends AbstractController
{
    
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('google/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

   
    // private function authenticateGoogleUser(Request $request, ClientRegistry $clientRegistry): Response
    // {
    //     // This method is not needed for the Google login flow
    //     // It can be used to authenticate the user if needed
    //     // dd($credentials);
    //     $client = new Client();
    //     $client->setApplicationName("Client_Library_Examples");
    //     $client->setDeveloperKey("YOUR_APP_KEY");

    //     $service = new Books($client);
    //     $query = 'Henry David Thoreau';
    //     $optParams = [
    //     'filter' => 'free-ebooks',
    //     ];
    //     $results = $service->volumes->listVolumes($query, $optParams);

    //     foreach ($results->getItems() as $item) {
    //     echo $item['volumeInfo']['title'], "<br /> \n";
    //     }
    // }

    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // private function getGoogleAuthorization(): string
    // {
    //     $provider = new Google([
    //         'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
    //         'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
    //         'redirectUri'  => 'http://127.0.0.1:8000', // or the exact URL declared in Google
    //         'hostedDomain' => 'http://127.0.0.1:8000', // optional; used to restrict access to users on your G Suite/Google Apps for Business accounts
    //     ]);
    //     // If we don't have an authorization code then get one
    //     if (!empty($_GET['error'])) {

    //         // Got an error, probably user denied access
    //         exit('Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));

    //     } elseif (empty($_GET['code'])) {

    //         // If we don't have an authorization code then get one
    //         $authUrl = $provider->getAuthorizationUrl();
    //         $request->getSession()->set('oauth2state', $provider->getState());
    //         header('Location: ' . $authUrl);
    //         exit;
    //     } elseif(empty($_GET['state']) || ($_GET['state'] !== $state)) {
            
    //         $request->getSession()->remove('oauth2state');
    //         exit('Invalid state');


    //     } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    //         // State is invalid, possible CSRF attack in progress
    //         unset($_SESSION['oauth2state']);
    //         exit('Invalid state');

    //     } else {

    //         // Try to get an access token (using the authorization code grant)
    //         $token = $provider->getAccessToken('authorization_code', [
    //             'code' => $_GET['code']
    //         ]);

    //         // Optional: Now you have a token you can look up a users profile data
    //         try {

    //             // We got an access token, let's now get the owner details
    //             $ownerDetails = $provider->getResourceOwner($token);

    //             // Use these details to create a new profile
    //             printf('Hello %s!', $ownerDetails->getFirstName());

    //         } catch (Exception $e) {

    //             // Failed to get user details
    //             exit('Something went wrong: ' . $e->getMessage());

    //         }

    //         // Use this to interact with an API on the users behalf
    //         echo $token->getToken();

    //         // Use this to get a new access token if the old one expires
    //         echo $token->getRefreshToken();

    //         // Unix timestamp at which the access token expires
    //         echo $token->getExpires();
    //     }
    // }

    // private function accessingTokenJwt(): string
    // {
    //     // This method is not needed for the Google login flow
    //     // It can be used to access the JWT token if needed
    //     // dd($credentials);
    //     $values = $token->getValues();

    //     /** @var string */
    //     $jwt = $values['id_token'];
    //     if (empty($jwt)) {
    //         throw new \RuntimeException('No JWT token found in the access token.');
    //     }
    //     // Decode the JWT token
    //     $decoded = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], explode('.', $jwt)[1])), true);
    //     if (json_last_error() !== JSON_ERROR_NONE) {
    //         throw new \RuntimeException('Failed to decode JWT token: ' . json_last_error_msg());
    //     }
    //     // You can now use the decoded JWT token as needed
    //     // For example, you can access the user information from the JWT token
    //     $userInfo = [
    //         'email' => $decoded['email'] ?? null,
    //         'name' => $decoded['name'] ?? null,
    //         'given_name' => $decoded['given_name'] ?? null,
    //         'family_name' => $decoded['family_name'] ?? null,
    //         'picture' => $decoded['picture'] ?? null,
    //     ];
    //     // Do something with the user information, like saving it to the database or session
    //     // For example, you can save it to the session
    //     $_SESSION['user_info'] = $userInfo;
    //     // Or you can return it as a response
    //     return $userInfo;
    // }

    // private function refreshTokenJwt(): string
    // {
    //     // This method is not needed for the Google login flow
    //     // It can be used to refresh the JWT token if needed
       

    //     $provider = new Google([
    //         'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
    //         'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
    //         'redirectUri'  => 'http://127.0.0.1:8000', // or the exact URL declared in Google
    //         'hostedDomain' => 'http://127.0.0.1:8000', // optional; used to restrict access to users on your G Suite/Google Apps for Business accounts
    //         'accessType'   => 'offline',
    //     ]);

    //     $token = $provider->getAccessToken('authorization_code', [
    //         'code' => $code
    //     ]);

    //     $grant = new RefreshToken();
    //     $token = $provider->getAccessToken($grant, ['refresh_token' => $refreshToken]);
    // }

    // private function getGoogleScopes(): array
    // {
    //     // This method is not needed for the Google login flow
    //     // It can be used to get the scopes for the Google API if needed
    //     $authorizationUrl = $provider->getAuthorizationUrl([
    //         'scope' => [
    //             'scope-url-here'
    //         ],
    //     ]);
    //     return [
    //         'authorizationUrl' => $authorizationUrl,
    //         'scopes' => [
    //             'scope-url-here'
    //         ]
    //     ];
    // }

    // private function googleCheck(): Response
    // {
    //     // This method is not needed for the Google login flow
    //     // It can be used to check the Google login status if needed
    //     // dd($credentials);
    //     $values = $token->getValues();

    //     /** @var string */
    //     $jwt = $values['id_token'];
    //     if (empty($jwt)) {
    //         throw new \RuntimeException('No JWT token found in the access token.');
    //     }
    //     // Decode the JWT token
    //     $decoded = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], explode('.', $jwt)[1])), true);
    //     if (json_last_error() !== JSON_ERROR_NONE) {
    //         throw new \RuntimeException('Failed to decode JWT token: ' . json_last_error_msg());
    //     }
    //     // You can now use the decoded JWT token as needed
    //     // For example, you can access the user information from the JWT token
    //     $userInfo = [
    //         'email' => $decoded['email'] ?? null,
    //         'name' => $decoded['name'] ?? null,
    //         'given_name' => $decoded['given_name'] ?? null,
    //         'family_name' => $decoded['family_name'] ?? null,
    //         'picture' => $decoded['picture'] ?? null,
    //     ];
    //     // Do something with the user information, like saving it to the database or session
    //     // For example, you can save it to the session
    //     $_SESSION['user_info'] = $userInfo;
    //     // Or you can return it as a response
    //     return new Response(json_encode($userInfo));
    // }

    
}
