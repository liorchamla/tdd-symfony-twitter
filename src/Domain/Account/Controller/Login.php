<?php

namespace App\Controller\Account;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Login extends AbstractController
{

    /**
     * @Route("/login", name="account_login")
     */
    public function __invoke(): Response
    {
        return $this->render('account/login.html.twig');
    }
}
