<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Follow;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    /** @test */
    public function we_should_ask_if_user_is_following_an_other()
    {
        self::bootKernel();

        /** @var EntityManagerInterface */
        $em = self::$container->get(EntityManagerInterface::class);

        $followed = (new User)->setUsername('followed')->setEmail('followed@mail.com')->setAvatar('toto.jpg')->setPassword('password');
        $follower = (new User)->setUsername('follower')->setEmail('follower@mail.com')->setAvatar('toto.jpg')->setPassword('password');
        $follow = (new Follow)->setFollowed($followed)->setFollower($follower)->setCreatedAt(new DateTime());

        $em->persist($followed);
        $em->persist($follower);
        $em->persist($follow);

        $em->flush();


        $repo = self::$container->get(UserRepository::class);

        $_follower = $repo->find($follower->getId());
        $_followed = $repo->find($followed->getId());

        $this->assertTrue($_follower->isFollowing($_followed));
        $this->assertFalse($_followed->isFollowing($_follower));
    }
}
