<?php

namespace App\Security;

use App\Domain\Account\Dto\LoginDto;
use App\Domain\Account\Form\LoginType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class FormAuthenticator extends AbstractGuardAuthenticator
{
    private Form $form;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(FormFactoryInterface $formFactory, UserPasswordEncoderInterface $encoder)
    {
        $this->form = $formFactory->create(LoginType::class);
        $this->encoder = $encoder;
    }

    public function supports(Request $request)
    {
        $this->form->handleRequest($request);

        return $this->form->isSubmitted() && $this->form->isValid();
    }

    public function getCredentials(Request $request)
    {
        return $this->form->getData();
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $usernameOrPassword = $credentials->usernameOrEmail;

        return $userProvider->loadUserByUsername($usernameOrPassword);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$this->encoder->isPasswordValid($user, $credentials->plainPassword)) {
            throw new AuthenticationException("Invalid credentials");
        }

        if (!$user->isActive()) {
            throw new AuthenticationException("User not activated yet. Did you check your email / spam ?");
        }

        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->attributes->set(Security::AUTHENTICATION_ERROR, $exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new RedirectResponse('/');
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
