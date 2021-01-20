<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function supportsClass(string $class)
    {
        return $class === User::class;
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->userRepository->find($user->getId());
    }

    public function loadUserByUsername(string $username)
    {
        $user = $this->userRepository->findByUsernameOrEmail($username);

        if (!$user) {
            throw new UsernameNotFoundException("User with username or email $username was not found");
        }

        return $user;
    }
}
