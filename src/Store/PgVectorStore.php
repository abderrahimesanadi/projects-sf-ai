<?php

namespace App\Store;

use Symfony\AI\Store\ManagedStoreInterface;
use Symfony\AI\Store\StoreInterface;
use Symfony\AI\Platform\Vector\Vector;
use Symfony\AI\Store\Document\VectorDocument;
use Symfony\AI\Store\Query\QueryInterface;

class PgVectorStore implements ManagedStoreInterface, StoreInterface
{

   public function supports(string $queryClass): bool {
        return Vector::class === $queryClass;
   }


    /**
     * @param string|array<string> $ids
     * @param array<string, mixed> $options
     */
    public function remove(string|array $ids, array $options = []): void{

    }

    public function setup(array $options = []): void
    {
        // Implementation to create the store
    }

    public function drop(array $options = []): void
    {
        // Implementation to drop the store (and related vectors)
    }

     public function add(VectorDocument|array $documents): void
    {
        // Implementation to add a document to the store
    }

    public function query(QueryInterface $query, array $options = []): iterable
    {
        // Implementation to query the store for documents
        $documents = [];
        return $documents;
    }
}