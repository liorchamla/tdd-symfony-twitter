<?php

namespace App\Tests\Feature\Account;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class LoginTest extends WebTestCase
{
    /** @test */
    public function anonymous_user_should_see_login_form()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertSelectorExists('form');
    }

    /** @test */
    public function it_should_authenticate_a_real_user_with_username()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        /** @var UserPasswordEncoderInterface */
        $encoder = self::$container->get(UserPasswordEncoderInterface::class);

        $user = new User;
        $user->setEmail('lior@mail.com')
            ->setUsername('Liorozore')
            ->setPassword($encoder->encodePassword($user, 'password'))
            ->setAvatar('toto.jpg')
            ->setActive(true);

        self::$container->get(EntityManagerInterface::class)->persist($user);
        self::$container->get(EntityManagerInterface::class)->flush();

        $client->submitForm('Connexion', [
            'login[usernameOrEmail]' => 'Liorozore',
            'login[plainPassword]' => 'password'
        ]);

        $this->assertResponseRedirects('/');
    }

    public function it_should_authenticate_a_real_user_with_email()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        /** @var UserPasswordEncoderInterface */
        $encoder = self::$container->get(UserPasswordEncoderInterface::class);

        $user = new User;
        $user->setEmail('lior@mail.com')
            ->setUsername('Liorozore')
            ->setPassword($encoder->encodePassword($user, 'password'))
            ->setAvatar('toto.jpg')
            ->setActive(true);

        self::$container->get(EntityManagerInterface::class)->persist($user);
        self::$container->get(EntityManagerInterface::class)->flush();

        $client->submitForm('Connexion', [
            'login[usernameOrEmail]' => 'lior@mail.com',
            'login[plainPassword]' => 'password'
        ]);

        $this->assertResponseRedirects('/');
    }

    /** @test */
    public function it_should_return_to_form_with_error_if_bad_username()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $client->submitForm('Connexion', [
            'login[usernameOrEmail]' => 'noone',
            'login[plainPassword]' => 'noone'
        ]);

        $exception = $client->getRequest()->attributes->get(Security::AUTHENTICATION_ERROR);
        $this->assertNotNull($exception);
        $this->assertNotEquals(302, $client->getResponse()->getStatusCode());

        $this->assertStringContainsString($exception->getMessage(), $client->getResponse()->getContent());
    }

    /** @test */
    public function it_should_return_to_form_with_error_if_bad_password()
    {
        $client = static::createClient();

        /** @var UserPasswordEncoderInterface */
        $encoder = self::$container->get(UserPasswordEncoderInterface::class);

        $user = new User;
        $user->setEmail('lior@mail.com')
            ->setUsername('Liorozore')
            ->setPassword($encoder->encodePassword($user, 'password'))
            ->setAvatar('toto.jpg')
            ->setActive(true);

        self::$container->get(EntityManagerInterface::class)->persist($user);
        self::$container->get(EntityManagerInterface::class)->flush();

        $client->request('GET', '/login');

        $client->submitForm('Connexion', [
            'login[usernameOrEmail]' => 'Liorozore',
            'login[plainPassword]' => 'noone'
        ]);

        $exception = $client->getRequest()->attributes->get(Security::AUTHENTICATION_ERROR);
        $this->assertNotNull($exception);
        $this->assertNotEquals(302, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString($exception->getMessage(), $client->getResponse()->getContent());
    }

    /** @test */
    public function it_should_return_to_form_with_error_if_user_not_activated()
    {
        $client = static::createClient();

        /** @var UserPasswordEncoderInterface */
        $encoder = self::$container->get(UserPasswordEncoderInterface::class);

        $user = new User;
        $user->setEmail('lior@mail.com')
            ->setUsername('Liorozore')
            ->setPassword($encoder->encodePassword($user, 'password'))
            ->setAvatar('toto.jpg')
            ->setActive(false);

        self::$container->get(EntityManagerInterface::class)->persist($user);
        self::$container->get(EntityManagerInterface::class)->flush();

        $client->request('GET', '/login');

        $client->submitForm('Connexion', [
            'login[usernameOrEmail]' => 'Liorozore',
            'login[plainPassword]' => 'password'
        ]);

        $exception = $client->getRequest()->attributes->get(Security::AUTHENTICATION_ERROR);
        $this->assertNotNull($exception);
        $this->assertNotEquals(302, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString($exception->getMessage(), $client->getResponse()->getContent());
    }
}
