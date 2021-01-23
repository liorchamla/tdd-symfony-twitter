<?php

namespace App\Domain\Following\Handler;

use App\Entity\Follow;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class FollowHandler
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addFollowLinkBetweenUsers(User $follower, User $followed)
    {
        $follow = (new Follow)
            ->setFollower($follower)
            ->setFollowed($followed)
            ->setCreatedAt(new DateTime());

        $this->em->persist($follow);
        $this->em->flush();
    }

    public function removeFollowLinkBetweenUsers(User $follower, User $followed): bool
    {
        $follow = $follower->getFollowingByUsername($followed->getUsername());

        if (!$follow) {
            return false;
        }

        $this->em->remove($follow);
        $this->em->flush();

        return true;
    }
}
