<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    private $em;
    private $userRepository;
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }
    #[Route('/users', name: 'app_users')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users'=>$users
        ]);
    }
}
