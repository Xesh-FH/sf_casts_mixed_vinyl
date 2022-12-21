<?php

namespace App\Controller;

use App\Entity\VinylMix;
use App\Repository\VinylMixRepository;
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
        $genres = ['pop', 'rock', 'metal', 'video games ost'];
        $mix = new VinylMix();
        $mix
            ->setTitle('Do You Remeber... Phil Collins ?!')
            ->setDescription('A pure mix of Drummers turned singers')
            ->setGenre($genres[array_rand($genres)])
            ->setTrackCount(rand(5, 20))
            ->setVotes(rand(-50, 50));

        $em->persist($mix);
        $em->flush();

        return new Response(sprintf(
            'Mix %d is %d tracks of 80\'s heaven !',
            $mix->getId(),
            $mix->getTrackCount()
        ));
    }

    #[Route('mix/{id}', name: 'app_mix_show')]
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
            $mix->setVotes($mix->getVotes() + 1);
        } else {
            $mix->setVotes($mix->getVotes() - 1);
        }
        $em->flush();

        $this->addFlash('success', 'Vote counted !');

        return $this->redirectToRoute('app_mix_show', [
            'id' => $mix->getId(),
        ]);
    }
}
