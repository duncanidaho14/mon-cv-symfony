<?php

namespace RightSide\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;


final readonly class OAuthRegistrationService
{

    public function __construct(private UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(ResourceOwnerInterface $resourceOwner): User
    {
        // Logic to handle OAuth registration
        // This could involve redirecting to an OAuth provider, handling the response, etc.
        
        // Example: Redirect to Google OAuth
       if (!$client instanceof OAuth2ClientInterface) {
            throw new \InvalidArgumentException('Expected an instance of OAuth2ClientInterface.');
        } else if (!$client->getRedirectUri()) {
            throw new \RuntimeException('Redirect URI is not set.');
        } else if (!$client->getClientId()) {
            throw new \RuntimeException('Client ID is not set.');
        } else if (!$client->getClientSecret()) {
            throw new \RuntimeException('Client Secret is not set.');
        } else if (!$client->getScopes()) {
            throw new \RuntimeException('Scopes are not set.');
        } else if (!$client->getState()) {
            throw new \RuntimeException('State is not set.');
        } else if (!$client->getAuthorizationMethod()) {
            throw new \RuntimeException('Authorization method is not set.');
        } else if (!$client->getAccessTokenMethod()) {
            throw new \RuntimeException('Access token method is not set.');
        } else if (!$client->getResourceOwnerDetailsUrl()) {
            throw new \RuntimeException('Resource owner details URL is not set.');
        } else if (!$client->getUserInfoUrl()) {
            throw new \RuntimeException('User info URL is not set.');
        } else if (!$client->getUserInfoMethod()) {
            throw new \RuntimeException('User info method is not set.');
        } else if (!$client->getAuthorizationUrl()) {
            throw new \RuntimeException('Authorization URL is not set.');
        }

        $user = match(true) {
            $resourceOwner instanceof GoogleUser => (new User())
                ->setGoogleId($resourceOwner->getId())
                ->setEmail($resourceOwner->getEmail())
                ->setFirstName($resourceOwner->getFirstName())
                ->setLastName($resourceOwner->getLastName())
        };

        $this->userRepository->save($user, true);
        return $user;
    }
}