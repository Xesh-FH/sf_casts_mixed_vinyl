<?php

namespace App\Controller;

use App\Entity\VinylMix;
use App\Repository\VinylMixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
