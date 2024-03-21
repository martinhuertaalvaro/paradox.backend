<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\TenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('api-auth')]
class AuthController extends AbstractController
{
  #[Route('/verify', name: 'verify_user_tenant', methods: ['GET'])]
  public function verify(EntityManagerInterface $entityManager, Request $request): JsonResponse
  {
    $userEmail = $request->query->get('username');
    $userPassword = $request->query->get('password');
    $userTenantId = $request->query->get('tenantId');
    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $repository = $entityManager->getRepository(User::class);
    $userVerified = $repository->findOneBy(['email' => $userEmail, 'tenantId' => $userTenantId]);

    $verified = $userVerified !== null ? true : false;

    // Crear y devolver una JsonResponse
    return new JsonResponse($verified, 200, [], false);
  }
}
