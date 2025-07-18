<?php

namespace RightSide\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Client\Provider\GoogleUser;
use RightSide\Security\AbstractLoginAuthenticator;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class GoogleAuthenticator extends AbstractLoginAuthenticator
{
    protected string $serviceName = 'google';

    public function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwner, UserRepository $userRepository): User
    {
        if (!($resourceOwner instanceof GoogleUser)) {
            throw new \InvalidArgumentException('Expected a GoogleUser instance.');
        }

        if(true !== ($resourceOwner->toArray()['email_verified'] ?? false)) {
            throw new \InvalidArgumentException('Email not verified.');
        }

        // This method is used to fetch the user from the OAuth2 client
        return $userRepository->findOneBy([
            'googleId' => $resourceOwner->getId(),
            'email' => $resourceOwner->getEmail()
        ]);    
    }
}
