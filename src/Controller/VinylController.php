<?php

namespace App\Controller;

use App\Repository\VinylMixRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use function Symfony\Component\String\u;

class VinylController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function hompage(): Response
    {
        $tracks = [
            ['song' => 'Gangsta\'s Paradise', 'artist' => 'Coolio'],
            ['song' => 'Waterfalls', 'artist' => 'TLC'],
            ['song' => 'Creep', 'artist' => 'Radiohead'],
            ['song' => 'Kiss from a Rose', 'artist' => 'Seal'],
            ['song' => 'On Bended Knee', 'artist' => 'Boyz II Men'],
            ['song' => 'Fantasy', 'artist' => 'Mariah Carey'],
        ];

        return $this->render('vinyl/homepage.html.twig', [
            'title' => 'PB and Jams',
            'tracks' => $tracks,
        ]);
    }

    #[Route('/browse/{slug}', name: 'app_browse')]
    public function browse(VinylMixRepository $mixRepository, Request $request, string $slug = null): Response
    {
        $musicStyle = $slug ? u(str_replace('-', ' ', $slug))->title(true) : null;
        $qb = $mixRepository->createOrderedByVotesQueryBuilder($slug);
        $adapter = new QueryAdapter($qb);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->getInt('page', 1), // On récupère le paramètre de requète 'page' s'il existe sinon on passe '1' par défaut et on cast en Int
            9
        );

        return $this->render('vinyl/browse.html.twig', [
            'genre' => $musicStyle,
            'pager' => $pagerfanta,
        ]);
    }
}
