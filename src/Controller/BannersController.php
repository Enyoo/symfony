<?php

namespace App\Controller;

use App\Entity\Banner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BannersController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/banners", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $body = json_decode($request->getContent());

        $banner = new Banner();
        $banner->src = $body->src;
        $banner->title = $body->title;

        $this->entityManager->persist($banner);
        $this->entityManager->flush();

        return new JsonResponse($banner);
    }

    /**
     * @Route ("banners", methods={"GET"})
     */
    public function all(Request $request): Response
    {
        $repositoryBanners = $this
            ->getDoctrine()
            ->getRepository(Banner::class);
        $list = $repositoryBanners->findAll();

        return new JsonResponse($list);
    }

    /**
     * @Route ("banners/{id}", methods={"GET"})
     */
    public function oneBanner(Request $request): Response
    {
        $id = $request->get('id');
        $repositoryBanners = $this
            ->getDoctrine()
            ->getRepository(Banner::class);
        $banner = $repositoryBanners->find($id);

        $responseCode = is_null($banner) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($banner, $responseCode);

    }
}