<?php

namespace App\Domain\Tweet\Controller;

use DateTime;
use App\Entity\Tweet;
use App\Repository\TweetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Retweet extends AbstractController
{

    protected TweetRepository $tweetRepository;
    protected EntityManagerInterface $em;

    public function __construct(TweetRepository $tweetRepository, EntityManagerInterface $em)
    {
        $this->tweetRepository = $tweetRepository;
        $this->em = $em;
    }

    /**
     * @Route("/retweet", name="tweet_retweet")
     * @IsGranted("ROLE_USER")
     */
    public function __invoke(Request $request): Response
    {
        $originalTweetId = $request->get('originalTweet');

        if (!$originalTweetId) {
            throw $this->createNotFoundException();
        }

        $originalTweet = $this->tweetRepository->find($originalTweetId);

        if (!$originalTweet) {
            throw $this->createNotFoundException();
        }

        $tweet = (new Tweet)
            ->setContent('')
            ->setCreatedAt(new DateTime())
            ->setAuthor($this->getUser())
            ->setRetweeting($originalTweet);

        $this->em->persist($tweet);
        $this->em->flush();

        return $this->redirectToRoute('home');
    }
}
