<?php

namespace App\Controller;


use App\Entity\Device;
use App\Repository\DeviceRepository;
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

#[Route('api-master/device')]
class DeviceController extends AbstractController
{
  #[Route('/all', name: 'get_all_device', methods: ['GET'])]
  public function all(EntityManagerInterface $entityManager): JsonResponse
  {
    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $repository = $entityManager->getRepository(Device::class);
    $users = $repository->findAll();

    // Convertir los objetos Users directamente a JSON
    $jsonContent = $serializer->serialize($users, 'json');

    // Crear y devolver una JsonResponse
    return new JsonResponse($jsonContent, 200, ['status' => 'user_listall'], true);
  }

  #[Route('/info', name: 'get_info_device', methods: ['GET'])]
  public function info(Request $request, EntityManagerInterface $entityManager)
  {
    $deviceName = $request->query->get('name');
    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $repository = $entityManager->getRepository(Device::class);
    $tenantInfo = $repository->findOneBy(['name' => $deviceName]);

    // Convertir los objetos Users directamente a JSON
    $jsonContent = $serializer->serialize($tenantInfo, 'json');

    // Crear y devolver una JsonResponse
    return new JsonResponse($jsonContent, 200, [], true);
  }
}
