<?php

namespace App\Tests\Feature\Tweet;

use App\Factory\UserFactory;
use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateTweetTest extends WebTestCase
{
    /** @test */
    public function as_an_anonymous_user_we_cant_see_tweet_form_on_home()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertSelectorNotExists('form.create-tweet');
    }

    /** @test */
    public function as_a_logged_user_we_can_see_tweet_form_on_home()
    {
        $user = UserFactory::createOne();

        $client = static::createClient();

        $client->loginUser($user->object());

        $client->request('GET', '/');

        $this->assertSelectorExists('form.create-tweet');
    }

    /** @test */
    public function as_a_logged_user_we_can_fill_in_a_tweet_and_create_it()
    {
        $user = UserFactory::createOne();

        $client = static::createClient();

        $client->loginUser($user->object());

        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Tweet')->form();

        $client->submit($form, [
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
