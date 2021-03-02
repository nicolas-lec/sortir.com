<?php

namespace App\Controller\Api;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LieuApiController extends AbstractController
{
    /**
     * @Route(path="/api/v1/lieu/{id}", name="api_lieu_api", methods={"POST", "GET"})
     * @param LieuRepository $repository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function listLieu(Request $request,LieuRepository $repository, SerializerInterface $serializer):Response{
        if($request -> isXmlHttpRequest()){
            $id = $request->request->get('id');
            $lieu = $repository->find((int)$id);
            $json = $serializer->serialize($lieu,'json',['groups'=>'list_lieu']);
            return new JsonResponse($json,200,[],true);
        }

    }
}