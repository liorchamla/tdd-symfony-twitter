<?php

namespace App\Tests\Feature\Tweet;

use App\Factory\TweetFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileTest extends WebTestCase
{
    /** @test */
    public function we_can_see_users_tweet_on_his_profile()
    {

        $client = static::createClient();

        $user = UserFactory::createOne();

        $tweets = TweetFactory::createMany(mt_rand(10, 30), [
            'author' => $user,
            'deletedAt' => null
        ]);

        $crawler = $client->request('GET', '/@' . $user->getUsername());
        $this->assertEquals(count($tweets), $crawler->filter('.tweet')->count());
    }
}
