<?php

namespace App\Tests\Feature\Following;

use App\Factory\FollowFactory;
use App\Factory\UserFactory;
use App\Repository\FollowRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FollowTest extends WebTestCase
{
    /** @test */
    public function an_unauthenticated_user_can_not_follow_an_other_user()
    {
        $client = static::createClient();

        $user = UserFactory::createOne();

        $client->request('POST', '/follow', [
            'username' => $user->getUsername()
        ]);

        $this->assertResponseRedirects('/login');
    }

    /** @test */
    public function an_authenticated_user_can_follow_an_other_user()
    {
        $client = static::createClient();

        $user = UserFactory::createOne();
        $follower = UserFactory::createOne();

        $client->loginUser($follower->object());

        $client->request('POST', '/follow', [
            'username' => $user->getUsername()
        ]);

        $this->assertResponseRedirects('/@' . $user->getUsername());

        /** @var FollowRepository */
        $repository = self::$container->get(FollowRepository::class);

        $follow = $repository->findOneBy([
            'follower' => $follower->object(),
            'followed' => $user->object()
        ]);

        $this->assertNotNull($follow);
    }
}
