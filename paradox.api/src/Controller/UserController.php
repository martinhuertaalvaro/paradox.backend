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
    foreach ($users as $user) {
      $user->setPassword('');
    }

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

  #[Route('/new', name: 'api_new_user', methods: 'POST')]
  public function createUser(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
  {
    $tenantId = $request->query->get('tenantId');
    $request = $this->transformJsonBody($request);
    $user = new User();
    $repository = $em->getRepository(Tenant::class);
    $tenantInfo = $repository->findOneBy(['id' => $tenantId]);
    $user->setTenantId($tenantInfo);
    $user->setEmail($request->get('email'));
    $user->setPassword(
      $passwordHasher->hashPassword(
        $user,
        $request->get('password')
      )
    );
    if($request->get('role') == 'admin') {
      $user->setRoles("ROLE_ADMIN");
    }
    $user->setName($request->get('name'));
    $user->setSurname($request->get('surname'));
    $user->setBirthdate($request->get('birthdate'));
    $user->setRegisterdate($request->get('dateOfRegister'));
    $user->setCity($request->get('city'));
    $user->setWorkfield($request->get('workfield'));
    $user->setActive(true);

    $em->persist($user);
    $em->flush();

    return new JsonResponse(['status' => 'User Created', 'email' => $request->get('email')]);
  }

  #[Route('/edit/active', name: 'api_edit_active', methods: 'POST')]
  public function editActive(Request $request, EntityManagerInterface $em): JsonResponse
  {
    $tenantId = $request->query->get('tenantId');
    $userEmail = $request->query->get('email');
    $request = $this->transformJsonBody($request);
    $repository = $em->getRepository(User::class);
    $user = $repository->findOneBy(['email' => $userEmail, 'tenantId' => $tenantId]);
    $user->setActive(!$user->isActive());


    $em->persist($user);
    $em->flush();

    return new JsonResponse(['status' => 'Active: '. $user->isActive()]);
  }

  #[Route('/edit/roles/add/admin', name: 'api_add_admin', methods: 'POST')]
  public function addAdmin(Request $request, EntityManagerInterface $em): JsonResponse
  {
    $tenantId = $request->query->get('tenantId');
    $userEmail = $request->query->get('email');
    $request = $this->transformJsonBody($request);
    $repository = $em->getRepository(User::class);
    $user = $repository->findOneBy(['email' => $userEmail, 'tenantId' => $tenantId]);
    $user->setRoles('ROLE_ADMIN');


    $em->persist($user);
    $em->flush();

    return new JsonResponse(['status' => 'Admin added']);
  }

  #[Route('/edit/roles/delete/admin', name: 'api_delete_admin', methods: 'POST')]
  public function removeAdmin(Request $request, EntityManagerInterface $em): JsonResponse
  {
    $tenantId = $request->query->get('tenantId');
    $userEmail = $request->query->get('email');
    $request = $this->transformJsonBody($request);
    $repository = $em->getRepository(User::class);
    $user = $repository->findOneBy(['email' => $userEmail, 'tenantId' => $tenantId]);
    $user->setAllRoles([]);


    $em->persist($user);
    $em->flush();

    return new JsonResponse(['status' => 'Admin added']);
  }

  #[Route('/delete', name: 'api_user_delete', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $em)
    {
      $tenantId = $request->query->get('tenantId');
      $userEmail = $request->query->get('email');
      $request = $this->transformJsonBody($request);
      $repository = $em->getRepository(User::class);
      $user = $repository->findOneBy(['email' => $userEmail, 'tenantId' => $tenantId]);

      $em->remove($user);
      $em->flush();
      return new JsonResponse(['status' => 'User deleted']);
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
