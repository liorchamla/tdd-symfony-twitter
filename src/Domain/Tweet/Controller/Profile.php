<?php

namespace App\Domain\Tweet\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Profile extends AbstractController
{

    /**
     * @Route("/@{username}", name="tweet_profile")
     */
    public function __invoke(User $user)
    {
        return $this->render('tweet/profile.html.twig', [
            'user' => $user
        ]);
    }
}
