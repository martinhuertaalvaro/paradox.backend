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

  /* #[Route('/new', name: 'api_new_user', methods: 'POST')]
  public function createUser(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
  {
    $request = $this->transformJsonBody($request);
    $user = new User();
    $user->setEmail($request->get('email'));
    $user->setPassword(
        $passwordHasher->hashPassword(
            $user,
            $request->get('password')
        )
    );
    $user->setRoles($request->get('role'));
    $user->setName($request->get('name'));
    $user->setSurname($request->get('surname'));
    $user->setBirthdate($request->get('birthdate'));

    $em->persist($user);
    $em->flush();

    return new JsonResponse(['status' => 'user_created']);
  }

  protected function transformJsonBody(Request $request)
  {
    $data = json_decode($request->getContent(), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return null;
    } else if ($data === null) {
        return $request;
    }

    $request->request->replace($data);
    return $request;
  } */
}
