<?php

namespace App\Tests\Integration\Following;

use App\Entity\Follow;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FollowTest extends KernelTestCase
{
    /** @test */
    public function we_should_find_a_list_of_users_followers()
    {
        static::bootKernel();

        $followed = new User;

        $followers = [
            new User,
            new User,
            new User,
        ];

        foreach ($followers as $f) {
            $followed->addFollower(
                (new Follow)->setFollowed($followed)
                    ->setFollower($f)
            );
        }

        $followedBy = $followed->followedByUsers();

        $this->assertCount(3, $followedBy);

        foreach ($followedBy as $f) {
            $this->assertInstanceOf(User::class, $f);
        }
    }

    /** @test */
    public function we_should_find_a_list_of_users_following()
    {
        static::bootKernel();

        $follower = new User;

        $followeds = [
            new User,
            new User,
            new User,
        ];

        foreach ($followeds as $f) {

            $f->addFollower(
                (new Follow)->setFollowed($f)
                    ->setFollower($follower)
            );
        }


        $following = $follower->followingUsers();

        $this->assertCount(3, $following);

        foreach ($following as $f) {
            $this->assertInstanceOf(User::class, $f);
        }
    }
}
