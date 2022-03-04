<?php

namespace App\Services;

use App\Entity\Products;
use App\Objects\ProductDTO;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisService
{
    private  $client;
    public function __construct(
        string $redisConn,
        private ManagerRegistry $doctrine,

    )
    {
        $this->client = RedisAdapter::createConnection($redisConn);
    }

    public function getCacheProduct(ProductDTO $productDTO): ProductDTO
    {
        $productDTO->getProduct($this->client->get(ProductDTO::PREFIX.$productDTO->id));

        return $productDTO;
    }

    public function setCacheProduct(ProductDTO $productDTO): ProductDTO
    {
        $json = json_encode($productDTO);
        $this->client->set(ProductDTO::PREFIX . $productDTO->id, $json, 'EX', 300);
        $productDTO->getProduct($this->client->get(ProductDTO::PREFIX.$productDTO->id));

        return $productDTO;
    }

    /**
     * @throws \Exception
     */
    public function cacheProduct(int $id): ProductDTO
    {
        $productDto = new ProductDTO();
        $productDto->id = $id;

        if(!$this->client->get('product_cache_' . $id))
        {
            $product = $this->doctrine->getRepository(Products::class)->findOneBy(['id' => $id]);
            $productDto->setProduct($product);
            return $this->setCacheProduct($productDto);
        }
        return $this->getCacheProduct($productDto);
    }
}