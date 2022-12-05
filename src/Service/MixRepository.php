<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Command\DebugCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{
    public function __construct(
        protected HttpClientInterface $githubContentClient,
        protected CacheInterface $cache,
        #[Autowire('%kernel.debug%')]
        protected bool $isDebug, //Paramètre 'non-autowireable' dont la valeur est configurée dans le services.yaml ou par attribut PHP8
        #[Autowire(service: 'twig.command.debug')]
        protected DebugCommand $twigDebugCommand
    ) {
    }

    public function findAll(): array
    {

        // $output = new BufferedOutput();
        // $this->twigDebugCommand->run(new ArrayInput([]), $output);
        // dd($output);

        return $this->cache->get('mixes_data', function (CacheItemInterface $cacheItem) {
            // on définit la durée de vie de cet item du cache à 10 secondes
            // si besoin de clear pool de cache spécifique à l'app => 'php/bin console cache:pool:clear cache.app'
            $cacheItem->expiresAfter($this->isDebug ? 10 : 100);
            $response = $this->githubContentClient->request('GET', '/SymfonyCasts/vinyl-mixes/main/mixes.json');
            return $response->toArray();
        });
    }
}
