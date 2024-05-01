<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
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

#[Route('api-master/user')]
class UserController extends AbstractController
{
  #[Route('/all', name: 'get_all_users', methods: ['GET'])]
  public function all(Request $request, EntityManagerInterface $entityManager): JsonResponse
  {
    $tenantId = $request->query->get('tenantId');
    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $repository = $entityManager->getRepository(User::class);
    $users = $repository->findBy(['tenantId' => $tenantId]);

    // Convertir los objetos Users directamente a JSON
    $jsonContent = $serializer->serialize($users, 'json');

    // Crear y devolver una JsonResponse
    return new JsonResponse($jsonContent, 200, ['status' => 'user_listall'], true);
  }

  #[Route('/info', name: 'get_info_users', methods: ['GET'])]
  public function info(Request $request, EntityManagerInterface $entityManager)
  {
    $tenantId = $request->query->get('tenantId');
    $email = $request->query->get('email');
    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $repository = $entityManager->getRepository(User::class);
    $tenantInfo = $repository->findOneBy(['email' => $email, 'tenantId' => $tenantId]);
    $tenantInfo->setPassword('');
    // Convertir los objetos Users directamente a JSON
    $jsonContent = $serializer->serialize($tenantInfo, 'json');

    // Crear y devolver una JsonResponse
    return new JsonResponse($jsonContent, 200, [], true);
  }
}
