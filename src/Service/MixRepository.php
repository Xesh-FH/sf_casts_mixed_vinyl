<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected CacheInterface $cache,
        private bool $isDebug //Paramètre non 'AutoWirable' dont la valeur est configurée dans le services.yaml
    ) {
    }

    public function findAll(): array
    {
        return $this->cache->get('mixes_data', function (CacheItemInterface $cacheItem) {
            // on définit la durée de vie de cet item du cache à 10 secondes
            // si besoin de clear pool de cache spécifique à l'app => 'php/bin console cache:pool:clear cache.app'
            $cacheItem->expiresAfter($this->isDebug ? 10 : 100);
            $response = $this->httpClient->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');
            return $response->toArray();
        });
    }
}
