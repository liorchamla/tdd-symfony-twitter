<?php

namespace App\Domain\Tweet\Controller;

use App\Domain\Tweet\Form\TweetType;
use App\Repository\TweetRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class Home extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function __invoke(RouterInterface $router, TweetRepository $tweetRepository): Response
    {
        $form = $this->createForm(TweetType::class, null, [
            'action' => $router->generate('tweet_create')
        ]);

        $tweets = $tweetRepository->findAllViewableTweetsByUser($this->getUser());

        return $this->render("tweet/home.html.twig", [
            'tweetForm' => $form->createView(),
            'tweets' => $tweets
        ]);
    }
}
