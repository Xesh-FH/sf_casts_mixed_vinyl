<?php

namespace App\Controller;

use App\Entity\VinylMix;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MixController extends AbstractController
{
    #[Route('/mix/new')]
    public function new(EntityManagerInterface $em): Response
    {
        return new Response(sprintf(
            'Mix %d is %d tracks of 80\'s heaven !',
            $mix->getId(),
            $mix->getTrackCount()
        ));
    }

    #[Route('mix/{slug}', name: 'app_mix_show')]
    public function show(VinylMix $mix): Response
    {
        return $this->render('mix/show.html.twig', [
            'mix' => $mix,
        ]);
    }

    #[Route('mix/{id}/vote', name: 'app_mix_vote', methods: ['POST'])]
    public function vote(EntityManagerInterface $em, VinylMix $mix, Request $request): Response
    {
        $direction = $request->request->get('direction', 'up');
        if ($direction === 'up') {
            $mix->upVote();
        } else {
            $mix->downVote();
        }
        $em->flush();

        $this->addFlash('success', 'Vote counted !');

        return $this->redirectToRoute('app_mix_show', [
            'slug' => $mix->getSlug(),
        ]);
    }
}
