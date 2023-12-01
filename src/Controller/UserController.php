<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class UserController extends AbstractController
{
    private $em;
    private $userRepository;
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }
    /*
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/users', name: 'app_users')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users'=>$users
        ]);
    }
    /*
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/users/save-user-role/{id}', name: 'save_user_role', methods: ['POST'])]
    public function saveUserRole(int $id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        if ($user) {
            $newRole = $request->request->get('role');
            if (!in_array($newRole, $user->getRoles())) {
                $user->setRoles([$newRole]); // Replace existing roles with the new role
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }

        // Redirect back to the users page. Replace 'user_management_page' with your actual route name.
        return $this->redirectToRoute('app_users');
    }
}
