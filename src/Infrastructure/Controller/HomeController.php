<?php

declare(strict_types = 1);

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class HomeController extends AbstractController
{
    #[Route('', name: 'home')]
    public function index() : RedirectResponse
    {
        return $this->redirectToRoute('user_list');
    }
}
