<?php

namespace App\Tests\Unit\Repository;

use App\Factory\FollowFactory;
use App\Factory\TweetFactory;
use App\Factory\UserFactory;
use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TweetRepositoryTest extends KernelTestCase
{
    /** @test */
    public function we_can_find_users_from_followed_users()
    {
        self::bootKernel();

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

        /** @var TweetRepository */
        $tweetRepository = self::$container->get(TweetRepository::class);

        $tweets = $tweetRepository->findAllViewableTweetsByUser($follower->object());

        $this->assertCount(count($followedsTweets) + count($followerTweets), $tweets);
    }
}
