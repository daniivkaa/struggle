<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\Image;
use App\Form\ImageType;
use App\Service\FileManagerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route("/competition/image/{competition}", name="upload_show_image")
     */
    public function index(Competition $competition, EntityManagerInterface $em, Request $request, FileManagerServiceInterface $fileManagerService): Response
    {
        $image = new Image();
        $addImage = $this->createForm(ImageType::class, $image);
        $addImage->handleRequest($request);

        if ($addImage->isSubmitted() && $addImage->isValid()) {
            $imageFile = $addImage->get("image")->getData();
            if($imageFile){
                $fileName = $fileManagerService->imagePostUpload($imageFile);

                $image->setOriginName($fileName);
                $image->setCompetition($competition);

                $em->persist($image);
                $em->flush();
            }
        }

        $images = $em->getRepository(Image::class)->findBy(["competition" => $competition]);

        return $this->render('image/index.html.twig', [
            'competition' => $competition,
            'images' => $images,
            'addImage' => $addImage->createView()
        ]);
    }

    /**
     * @Route("/user/history/images/{competition}", name="user_show_images")
     */
    public function show(Competition $competition, EntityManagerInterface $em): Response
    {
        $images = $em->getRepository(Image::class)->findBy(["competition" => $competition]);

        return $this->render('image/show.html.twig', [
            'competition' => $competition,
            'images' => $images,
        ]);
    }
}
