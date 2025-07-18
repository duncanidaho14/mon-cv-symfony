<?php

namespace RightSide\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use RightSide\Security\OAuthRegistrationService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

abstract class AbstractLoginAuthenticator extends OAuth2Authenticator
{
    use TargetPathTrait;
    protected string $serviceName = '';

    public function __construct(
        private readonly ClientRegistry $clientRegistry,
        private readonly RouterInterface $router,
        private readonly UserRepository $userRepository,
        private readonly OAuthRegistrationService $registrationService
        )
    {
        // This constructor is used to inject dependencies like ClientRegistry and UrlGeneratorInterface
        // These will be used in the authenticate and onAuthenticationSuccess methods
    }

    public function supports(Request $request): ?bool
    {
        // This method checks if the request is for the login route
        return 'google_check' === $request->attributes->get('_route') &&
            $request->get('service') === $this->serviceName;
    }

    public function onAuthicationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }
        // Redirect to the homepage or any other route after successful authentication
        return new RedirectResponse($this->clientRegistry->getClient($this->serviceName)->getRedirectUri());
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($request->hasSession()) {
            // Handle authentication failure (e.g., redirect to login with error message)
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }
        return new RedirectResponse($this->router->generate('cv_login'));
    }

    public function authenticate(Request $request): Passport
    {
        $credentials = $this->fetchAccessToken($this->getClient());
        $resourceOwner = $this->getResourceOwnerFromCredentials($credentials);
        $user = $this->getUserFromResourceOwner($resourceOwner, $this->userRepository);

        if (null === $user) {
           $user = $this->registrationService->register($resourceOwner, $this->userRepository);
        }

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    protected function getResourceOwnerFromCredentials(AccessToken $credentials): UserInterface
    {
        // This method is used to fetch the user from the OAuth2 client
        return $this->getClient()->fetchUserFromToken($credentials);
    }

    private function getClient(): OAuth2ClientInterface
    {
        // Get the OAuth2 client from the ClientRegistry
        return $this->clientRegistry->getClient($this->serviceName);
    }

    abstract public function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwner, UserRepository $userRepository): User;
}