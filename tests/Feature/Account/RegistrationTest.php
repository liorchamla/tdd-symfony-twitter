<?php

namespace App\Tests\Feature\Account;

use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
{
    /** @test */
    public function anonymous_user_can_access_registration_form()
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="register[email]"]');
        $this->assertSelectorExists('input[name="register[password]"]');
        $this->assertSelectorExists('input[name="register[username]"]');
    }

    /** @test */
    public function anonymous_user_cant_fill_with_an_existing_username()
    {
        $client = static::createClient();

        $user = UserFactory::createOne();

        $client->request('GET', '/register');

        $client->submitForm('Créer mon compte', [
            'register[email]' => 'lior@mail.com',
            'register[password]' => 'password',
            'register[username]' => $user->getUsername(),
            'register[avatar]' => 'https://placehold.it/200x200'
        ]);

        $this->assertResponseStatusCodeSame(200);

        /** @var UserRepository */
        $userRepository = self::$container->get(UserRepository::class);

        $this->assertCount(1, $userRepository->findBy(['username' => $user->getUsername()]));
    }

    /** @test */
    public function anonymous_user_can_fill_the_form_and_register()
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $client->submitForm('Créer mon compte', [
            'register[email]' => 'lior@mail.com',
            'register[password]' => 'password',
            'register[username]' => 'Liorozore',
            'register[avatar]' => 'https://placehold.it/200x200'
        ]);

        /** @var UserRepository */
        $userRepository = self::$container->get(UserRepository::class);

        $user = $userRepository->findOneBy([
            'username' => 'Liorozore'
        ]);

        $this->assertFalse($user->isActive());
        $this->assertNotEquals("password", $user->getPassword());
        $this->assertNotNull($user->getConfirmationToken());
        $this->assertResponseRedirects('/login');
    }

    /** @test */
    public function the_user_should_receive_a_mail_after_registration()
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $client->submitForm('Créer mon compte', [
            'register[email]' => 'lior@mail.com',
            'register[password]' => 'password',
            'register[username]' => 'Liorozore',
            'register[avatar]' => 'https://placehold.it/200x200'
        ]);

        $this->assertEmailCount(1);
    }
}
