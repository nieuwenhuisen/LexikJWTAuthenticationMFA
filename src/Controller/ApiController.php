<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/verify", name="api_mfa_verify", methods="post")
     */
    public function verify(Request $request, JWTTokenManagerInterface $manager) 
    {
        //dd($request);
        $user = $this->getUser();
        $token = $manager->create($user);

        return $this->json([
            'token' => $token
        ]);
    }

    /**
     * @Route("/api/users", name="api_users")
     */
    public function index(UserRepository $userRepository)
    {
        $output = [];
        $users = $userRepository->findAll();

        /** User $user */
        foreach ($users as $user) {
            $output[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ];
        }

        return $this->json($output);
    }
}
