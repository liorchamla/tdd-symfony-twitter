<?php

namespace App\Tests\Feature\Following;

use App\Factory\FollowFactory;
use App\Factory\UserFactory;
use App\Repository\FollowRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UnfollowTest extends WebTestCase
{



    /** @test */
    public function follower_can_see_unfollow_button_on_user_s_page()
    {
        $user = UserFactory::createOne();
        $follower = UserFactory::createOne();
        $follow = FollowFactory::createOne([
            'follower' => $follower,
            'followed' => $user
        ]);

        $client = static::createClient();

        $client->loginUser($follower->object());

        $client->request('GET', '/@' . $user->getUsername());

        $this->assertResponseIsSuccessful();

        $this->assertSelectorNotExists('.follow');
        $this->assertSelectorExists('.unfollow');
    }

    /** @test */
    public function user_cant_see_unfollow_button_if_he_does_not_follow_user_already()
    {
        $user = UserFactory::createOne();
        $follower = UserFactory::createOne();

        $client = static::createClient();

        $client->loginUser($follower->object());

        $client->request('GET', '/@' . $user->getUsername());

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.follow');
        $this->assertSelectorNotExists('.unfollow');
    }

    /** @test */
    public function follower_can_unfollow_an_other_user()
    {
        $user = UserFactory::createOne();
        $follower = UserFactory::createOne();
        $follow = FollowFactory::createOne([
            'follower' => $follower,
            'followed' => $user
        ]);

        $client = static::createClient();

        $client->loginUser($follower->object());

        $client->request('POST', '/unfollow', [
            'username' => $user->getUsername()
        ]);

        /** @var FollowRepository */
        $followRepository = self::$container->get(FollowRepository::class);

        $this->assertCount(0, $followRepository->findBy([
            'id' => $follow->getId()
        ]));

        $this->assertResponseRedirects('/@' . $user->getUsername());
    }

    /** @test */
    public function you_cant_unfollow_someone_u_was_not_already_following()
    {
        $user = UserFactory::createOne();
        $follower = UserFactory::createOne();

        $client = static::createClient();

        $client->loginUser($follower->object());

        $client->request('POST', '/unfollow', [
            'username' => $user->getUsername()
        ]);

        $this->assertResponseStatusCodeSame(404);
    }

    /** @test */
    public function we_can_not_unfollow_without_username_parameter()
    {
        $follower = UserFactory::createOne();

        $client = static::createClient();

        $client->loginUser($follower->object());

        $client->request('POST', '/unfollow');

        $this->assertResponseStatusCodeSame(404);
    }

    /** @test */
    public function we_can_not_access_unfollow_if_not_logged_in()
    {
        $client = static::createClient();

        $client->request('POST', '/unfollow');

        $this->assertResponseRedirects('/login');
    }
}
