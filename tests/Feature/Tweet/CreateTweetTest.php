<?php

namespace App\Tests\Feature\Tweet;

use App\Factory\UserFactory;
use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateTweetTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /** @test */
    public function as_an_anonymous_user_we_cant_see_tweet_form_on_home()
    {
        $this->client->request('GET', '/');

        $this->assertSelectorNotExists('form.create-tweet');
    }

    /** @test */
    public function as_a_logged_user_we_can_see_tweet_form_on_home()
    {
        $user = UserFactory::createOne();

        $this->client->loginUser($user->object());

        $this->client->request('GET', '/');

        $this->assertSelectorExists('form.create-tweet');
    }

    /** @test */
    public function as_a_logged_user_we_can_fill_in_a_tweet_and_create_it()
    {
        $user = UserFactory::createOne();

        $this->client->loginUser($user->object());

        $crawler = $this->client->request('GET', '/');

        $form = $crawler->selectButton('Tweet')->form();

        $this->client->submit($form, [
            'tweet[content]' => "Hello World!"
        ]);

        /** @var TweetRepository */
        $tweetRepository = self::$container->get(TweetRepository::class);

        $tweet = $tweetRepository->findOneBy([
            'content' => 'Hello World!'
        ]);

        $this->assertNotNull($tweet);
        $this->assertEquals($user->getId(), $tweet->getAuthor()->getId());
    }
}
