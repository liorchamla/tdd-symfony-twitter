<?php

namespace App\Domain\Following\Controller;

use App\Domain\Following\Handler\FollowHandler;
use App\Entity\User;
use App\Repository\FollowRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Unfollow extends AbstractController
{

    protected FollowRepository $followRepository;
    protected UserRepository $userRepository;
    protected FollowHandler $followHandler;

    public function __construct(FollowRepository $followRepository, UserRepository $userRepository, FollowHandler $followHandler)
    {
        $this->followRepository = $followRepository;
        $this->userRepository = $userRepository;
        $this->followHandler = $followHandler;
    }

    /**
     * @Route("/unfollow", name="following_unfollow")
     * @IsGranted("ROLE_USER")
     */
    public function __invoke(Request $request): Response
    {
        $username = $request->get('username');

        if (!$username) {
            throw $this->createNotFoundException();
        }

        $followed = $this->userRepository->findByUsernameOrEmail($username);

        if (!$followed) {
            throw $this->createNotFoundException();
        }

        if (!$this->isGranted('CAN_UNFOLLOW', $followed)) {
            throw $this->createNotFoundException();
        }

        $this->followHandler->removeFollowLinkBetweenUsers($this->getUser(), $followed);

        return $this->redirectToRoute('tweet_profile', ['username' => $request->get('username')]);
    }
}
