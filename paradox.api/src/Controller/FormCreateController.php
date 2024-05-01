<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Team;
use App\Entity\Device;
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

#[Route('api-master/create/form-create')]
class FormCreateController extends AbstractController
{
  #[Route('/properties', name: 'get_properties', methods: ['GET'])]
  public function getPorperties(Request $request, EntityManagerInterface $entityManager): JsonResponse
  {
    $typeEntity = $request->query->get('entity');
    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $entity = ($typeEntity == 'user') ? new User : (($typeEntity == 'device') ? new Device : new Team);

    // Convertir los objetos Users directamente a JSON
    $jsonContent = $serializer->serialize($entity, 'json');

    // Crear y devolver una JsonResponse
    return new JsonResponse($jsonContent, 200, ['status' => 'user_listall'], true);
  }
}
