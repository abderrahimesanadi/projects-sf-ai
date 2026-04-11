<?php

namespace App\AI\Loaders;

use Doctrine\DBAL\Connection;
use Symfony\AI\Store\Document\TextDocument;
use Symfony\AI\Store\Document\Metadata;
use Symfony\AI\Store\Document\LoaderInterface;
use Symfony\Component\Uid\Uuid;

class StockDatabaseLoader implements LoaderInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function load(?string $source = null, array $options = []): iterable
    {
        // Example: load products
        $rows = $this->connection->fetchAllAssociative("
            SELECT id, produit, stock, seuil
            FROM stock
        ");

        foreach ($rows as $row) {

            $content = sprintf(
                "produit: %s\nstock: %d\nseuil: %d",
                $row['produit'],
                $row['stock'],
                $row['seuil']
            );
            yield new TextDocument(
                Uuid::v4()->toRfc4122(),
                $content,
                new Metadata([
                    'produit' => $row['produit'],
                    'stock' => $row['stock'],
                    'seuil' => $row['seuil']
                ])
            );
        }
    }
}