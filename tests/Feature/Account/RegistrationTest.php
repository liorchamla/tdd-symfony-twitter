<?php

namespace App\Tests\Feature\Account;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Happyr\ServiceMocking\ServiceMock;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\MailerInterface;

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

        $user = new User;
        $user->setUsername('Liorozore')
            ->setEmail('lior@mail.com')
            ->setPassword('password')
            ->setAvatar('/');

        /** @var EntityManagerInterface */
        $em = self::$container->get(EntityManagerInterface::class);

        $em->persist($user);
        $em->flush();

        $client->request('GET', '/register');

        $client->submitForm('Créer mon compte', [
            'register[email]' => 'lior@mail.com',
            'register[password]' => 'password',
            'register[username]' => 'Liorozore',
            'register[avatar]' => 'https://placehold.it/200x200'
        ]);

        $this->assertResponseStatusCodeSame(200);

        /** @var UserRepository */
        $userRepository = self::$container->get(UserRepository::class);

        $this->assertCount(1, $userRepository->findBy(['username' => 'Liorozore']));
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
