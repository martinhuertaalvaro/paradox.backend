<?php

namespace App\Controller;

use App\Entity\Tenant;
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

#[Route('api-master/members')]
class MembersController extends AbstractController
{

  #[Route('/all', name: 'get_all_members', methods: ['GET'])]
  public function members(Request $request, EntityManagerInterface $entityManager): JsonResponse
  {
    $tenantId = $request->query->get('tenantId');
    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $repository = $entityManager->getRepository(User::class);
    $users = $repository->findBy(['tenantId' => $tenantId, 'active' => true]);
    foreach ($users as $user) {
      $user->setPassword('');
    }

    // Convertir los objetos Users directamente a JSON
    $jsonContent = $serializer->serialize($users, 'json');

    // Crear y devolver una JsonResponse
    return new JsonResponse($jsonContent, 200, ['status' => 'user_listall'], true);
  }

  #[Route('/friends', name: 'get_all_friends', methods: ['GET'])]
  public function allFriends(Request $request, EntityManagerInterface $entityManager): JsonResponse
  {
    $tenantId = $request->query->get('tenantId');
    $userEmail = $request->query->get('email');
    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $repository = $entityManager->getRepository(User::class);
    $user = $repository->findOneBy(['email' => $userEmail, 'tenantId' => $tenantId]);
    var_dump($user->getFriends());
    $user = $user->getFriends();


    // Convertir los objetos Users directamente a JSON
    $jsonContent = $serializer->serialize($user, 'json');

    // Crear y devolver una JsonResponse
    return new JsonResponse($jsonContent, 200, ['status' => 'user_listall'], true);
  }

  #[Route('/friend/new', name: 'api_new_friend', methods: 'POST')]
  public function newFriend(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
  {
    $tenantId = $request->query->get('tenantId');
    $userEmail = $request->query->get('email');
    $friendId = $request->query->get('friendId');
    $request = $this->transformJsonBody($request);
    $user = new User();
    $repository = $em->getRepository(User::class);
    $user = $repository->findOneBy(['email' => $userEmail, 'tenantId' => $tenantId]);
    $user->setOneFriend($friendId);


    $em->persist($user);
    $em->flush();

    return new JsonResponse(['status' => 'Friend Added']);
  }

  #[Route('/friend/delete', name: 'api_delete_friend', methods: 'POST')]
  public function deleteFriend(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
  {
    $tenantId = $request->query->get('tenantId');
    $userEmail = $request->query->get('email');
    $friendId = $request->query->get('friendId');
    $request = $this->transformJsonBody($request);
    $user = new User();
    $repository = $em->getRepository(User::class);
    $user = $repository->findOneBy(['email' => $userEmail, 'tenantId' => $tenantId]);
    $friends = $user->getFriends($friendId);

    $friendIndex = array_search($friendId, $friends);
    unset($friends[$friendIndex]);
    $user->setFriends($friends);


    $em->persist($user);
    $em->flush();

    return new JsonResponse(['status' => 'Friend Deleted']);
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
  }
}
