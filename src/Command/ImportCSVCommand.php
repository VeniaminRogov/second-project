<?php

namespace App\Command;

use App\Entity\Categories;
use App\Entity\Products;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ImportCSVCommand extends Command
{
    protected static $defaultName = 'app:update-stock';
    public function __construct($projectDir, private ManagerRegistry $doctrine)
    {
        $this->projectDir = $projectDir;
        parent::__construct();
    }


    protected function configure()
    {
        $this->setDescription('Update stock records')
            ->addArgument('process_date', InputArgument::OPTIONAL, 'Date of process', date_create()->format('Y-M-D'));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $processDate = $input->getArgument('process_date');

        $importProducts = $this->getCsvRowsAsArray($processDate);

        $products = $this->doctrine->getRepository(Products::class);

        foreach ($importProducts as $importProduct)
        {
            if($existingItem = $products->findOneBy(['name' => $importProduct['name']]))
            {
                $this->updateProduct($existingItem, $importProduct);

                continue;
            }
            $this->createProduct($importProduct);
        }

        return Command::SUCCESS;
    }

    private function getCsvRowsAsArray($processDate)
    {
        $inputFile = $this->projectDir.'/public/import-products/'.$processDate.'.csv';

        $decoder = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

        return $decoder->decode(file_get_contents($inputFile), 'csv');
    }

    private function updateProduct(mixed $existingItem, mixed $importProduct)
    {
        $existingItem->setPrice($importProduct['price']);
        $existingItem->setQuantity($importProduct['quantity']);
        $existingItem->setIsAvailable($importProduct['is_available']);

        $this->doctrine->getManager()->persist($existingItem);
        $this->doctrine->getManager()->flush();
    }

    private function createProduct(mixed $importProduct)
    {
        $product = new Products();
        $category = new Categories();
        $product->setName($importProduct['name']);
        $product->setDescription($importProduct['description']);
        $product->setPrice($importProduct['price']);
        $product->setQuantity($importProduct['quantity']);
        $product->setIsAvailable($importProduct['is_available']);
        $prdouctCategory = $category->setName($importProduct['category']);
        $product->setCategoryId($prdouctCategory);
        $this->doctrine->getManager()->persist($product);

    }
}