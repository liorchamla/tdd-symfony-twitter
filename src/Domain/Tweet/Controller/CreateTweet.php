<?php

namespace App\Domain\Tweet\Controller;

use App\Domain\Tweet\Form\TweetType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateTweet extends AbstractController
{
    /**
     * @Route("/tweet/create", name="tweet_create")
     * @IsGranted("ROLE_USER")
     */
    public function __invoke(Request $request, EntityManagerInterface $em): Response
    {
        $form = ($this->createForm(TweetType::class))->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tweet = $form->getData()->toEntity();

            $tweet->setAuthor($this->getUser())
                ->setCreatedAt(new DateTime());

            $em->persist($tweet);
            $em->flush();
        }

        return $this->redirectToRoute('home');
    }
}
