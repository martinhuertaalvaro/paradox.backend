<?php

namespace App\Controller;


use App\Entity\Tenant;
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

#[Route('api-master/tenant')]
class TenantController extends AbstractController
{
    #[Route('/all', name: 'get_all_tenant', methods: ['GET'])]
    public function all(EntityManagerInterface $entityManager): JsonResponse
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $repository = $entityManager->getRepository(Tenant::class);
        $users = $repository->findAll();

        // Convertir los objetos Users directamente a JSON
        $jsonContent = $serializer->serialize($users, 'json');

        // Crear y devolver una JsonResponse
        return new JsonResponse($jsonContent, 200, ['status' => 'user_listall'], true);
    }

    #[Route('/info', name: 'get_info_tenant', methods: ['GET'])]
    public function info(Request $request, EntityManagerInterface $entityManager)
    {
        $tenantCode = $request->query->get('code');
        $tenantIsActive = ($request->query->get('active') === 'true');
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $repository = $entityManager->getRepository(Tenant::class);
        $tenantInfo = $repository->findOneBy(['code' => $tenantCode, 'active' => $tenantIsActive]);

        // Convertir los objetos Users directamente a JSON
        $jsonContent = $serializer->serialize($tenantInfo, 'json');

        // Crear y devolver una JsonResponse
        return new JsonResponse($jsonContent, 200, [], true);
    }
}
