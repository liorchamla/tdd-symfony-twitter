<?php

namespace App\Controller\Account;

use App\Controller\Account\Dto\RegistrationDto;
use App\Event\RegisterEvent;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Registration extends AbstractController
{
    private EntityManagerInterface $em;
    private EventDispatcherInterface $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route("/register", name="account_register")
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(RegisterType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData()->toEntity();

            $this->dispatcher->dispatch(new RegisterEvent($user), RegisterEvent::NAME);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Inscription terminée, un email vous a été envoyé afin de valider votre adresse et activer votre compte');
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
