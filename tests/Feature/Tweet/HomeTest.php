<?php

namespace App\Tests\Feature\Tweet;

use App\Factory\FollowFactory;
use App\Factory\TweetFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }
    /** @test */
    public function anonymous_user_should_not_see_form_or_tweets()
    {


        $this->client->request('GET', '/');

        $this->assertSelectorNotExists('.tweet');
        $this->assertSelectorNotExists('.tweet-form');
    }

    /** @test */
    public function anonymous_user_should_see_an_invitation_to_login_or_register()
    {


        $this->client->request('GET', '/');

        $this->assertSelectorExists('.login');
        $this->assertSelectorExists('.register');
    }

    /** @test */
    public function authenticated_follower_see_his_tweets_and_his_following_tweets()
    {

        $follower = UserFactory::createOne();

        $followeds = UserFactory::createMany(5);

        $followerTweets = TweetFactory::createMany(5, [
            'author' => $follower->object(),
            'deletedAt' => null
        ]);

        $followedsTweets = [];
        foreach ($followeds as $f) {
            FollowFactory::createOne([
                'follower' => $follower->object(),
                'followed' => $f->object()
            ]);

            $followedsTweets = array_merge(
                $followedsTweets,
                TweetFactory::createMany(3, [
                    'author' => $f->object(),
                    'deletedAt' => null
                ])
            );
        }

        $this->client->loginUser($follower->object());

        $crawler = $this->client->request('GET', '/');

        $totalNumberOfTweets = count($followedsTweets) + count($followerTweets);

        $this->assertEquals($totalNumberOfTweets, $crawler->filter('.tweet')->count());
    }
}
