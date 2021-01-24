<?php

namespace App\Domain\Following\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class FollowVoter extends Voter
{

    /**
     * @param string $attribute
     * @param User $subject
     *
     * @return bool
     */
    protected function supports(string $attribute, $subject)
    {
        return in_array($attribute, ['CAN_FOLLOW', 'CAN_UNFOLLOW']) && $subject instanceof User;
    }

    /**
     * @param string $attribute
     * @param User $followed
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $followed, TokenInterface $token)
    {
        /** @var User */
        $follower = $token->getUser();

        if (!$follower instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'CAN_FOLLOW':
                return $follower->isFollowing($followed) === false;
            case 'CAN_UNFOLLOW':
                return $follower->isFollowing($followed);
            default:
                return false;
        }
    }
}
