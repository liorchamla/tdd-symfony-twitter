<?php

namespace App\Tests\Feature\Tweet;

use App\Entity\User;
use App\Factory\FollowFactory;
use App\Factory\TweetFactory;
use App\Factory\UserFactory;
use App\Repository\TweetRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RetweetTest extends WebTestCase
{
    protected KernelBrowser $client;
    protected User $follower;
    protected User $followed;
    protected User $origin;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->follower = UserFactory::createOne()->object();
        $this->followed = UserFactory::createOne()->object();
        $this->origin = UserFactory::createOne()->object();
    }

    /** @test */
    public function authenticated_user_can_see_retweet_button_on_user_s_tweet()
    {
        TweetFactory::createMany(3, [
            'author' => $this->origin,
            'deletedAt' => null
        ]);

        $this->client->loginUser($this->follower);

        $crawler = $this->client->request('GET', '/@' . $this->origin->getUsername());

        $this->assertEquals(3, $crawler->filter('.tweet')->count());
        $this->assertEquals(3, $crawler->filter('.retweet')->count());
    }

    /** @test */
    public function unauthenticated_user_can_not_see_retweet_button()
    {
        TweetFactory::createMany(3, [
            'author' => $this->origin
        ]);

        $crawler = $this->client->request('GET', '/@' . $this->origin->getUsername());

        $this->assertEquals(3, $crawler->filter('.tweet')->count());
        $this->assertSelectorNotExists('.retweet');
    }

    /** @test */
    public function user_should_see_other_users_retweeted_tweets()
    {
        FollowFactory::createOne([
            'follower' => $this->follower,
            'followed' => $this->followed
        ]);

        FollowFactory::createOne([
            'follower' => $this->followed,
            'followed' => $this->origin
        ]);

        $originalTweet = TweetFactory::createOne([
            'author' => $this->origin,
            'deletedAt' => null
        ])->object();

        TweetFactory::createOne([
            'author' => $this->followed,
            'retweeting' => $originalTweet,
            'deletedAt' => null
        ]);

        $this->client->loginUser($this->follower);

        $this->client->request('GET', '/@' . $this->followed->getUsername());

        $this->assertStringContainsString($originalTweet->getContent(), $this->client->getResponse()->getContent());
        $this->assertStringContainsString($this->origin->getUsername(), $this->client->getResponse()->getContent());
    }

    /** @test */
    public function an_authenticated_user_can_retweet_an_original_tweet()
    {
        $originalTweet = TweetFactory::createOne([
            'author' => $this->origin,
            'deletedAt' => null
        ]);

        $this->client->loginUser($this->follower);

        $this->client->request('POST', '/retweet', [
            'originalTweet' => $originalTweet->getId()
        ]);

        $this->assertResponseRedirects('/');

        /** @var TweetRepository */
        $repository = self::$container->get(TweetRepository::class);

        $tweet = $repository->findOneBy(['author' => $this->follower]);
        $this->assertEquals($originalTweet->getId(), $tweet->getRetweeting()->getId());
    }

    /** @test */
    public function an_unauthenticated_user_can_not_retweet()
    {
        $originalTweet = TweetFactory::createOne([
            'author' => $this->origin,
            'deletedAt' => null
        ]);

        $this->client->request('POST', '/retweet', [
            'originalTweet' => $originalTweet->getId()
        ]);

        $this->assertResponseRedirects('/login');
    }
}
