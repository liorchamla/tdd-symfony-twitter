<?php

namespace App\Domain\Following\Controller;

use App\Domain\Following\Handler\FollowHandler;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Follow extends AbstractController
{
    protected UserRepository $userRepository;
    protected FollowHandler $followHandler;

    public function __construct(UserRepository $userRepository, FollowHandler $followHandler)
    {
        $this->userRepository = $userRepository;
        $this->followHandler = $followHandler;
    }

    /**
     * @Route("/follow", name="following_follow")
     * @IsGranted("ROLE_USER")
     */
    public function __invoke(Request $request): Response
    {
        if (!$request->request->has('username')) {
            throw $this->createNotFoundException();
        }

        $followed = $this->userRepository->findOneByUsername($request->get('username'));

        if (!$followed) {
            throw  $this->createNotFoundException();
        }

        if (!$this->isGranted('CAN_FOLLOW', $followed)) {
            throw $this->createNotFoundException();
        }

        $this->followHandler->addFollowLinkBetweenUsers($this->getUser(), $followed);

        return $this->redirectToRoute('tweet_profile', ['username' => $request->request->get('username')]);
    }
}
