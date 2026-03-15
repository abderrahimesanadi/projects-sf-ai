<?php

namespace App\AI;

use Doctrine\DBAL\Connection;
use Symfony\AI\Store\StoreInterface;
use Symfony\AI\Store\Document\VectorDocument;
use Symfony\AI\Store\Query\QueryInterface;
use Symfony\AI\Store\Query\VectorQuery;
use Symfony\AI\Store\Document\Metadata;

class PgVectorStore implements StoreInterface
{
    public function __construct(
        private Connection $connection,
        private string $table = 'ai_embeddings'
    ) {
    }

    public function supports(string $queryClass): bool
    {
        return VectorQuery::class === $queryClass;
    }
    
    public function add(VectorDocument|array $documents): void
    {
        if ($documents instanceof VectorDocument) {
            $documents = [$documents];
        }

        foreach ($documents as $document) {
            $this->connection->insert($this->table, [
                'id' => $document->getId(),
                'content' => $document->getScore(),
                'metadata' => json_encode($document->getMetadata()),
                'embedding' => $this->toPgVector($document->getVector()->getData()),
            ]);
        }
    }
    
    public function query(QueryInterface $query, array $options = []): iterable
    {
        if (!$query instanceof VectorQuery) {
            throw new \InvalidArgumentException('Unsupported query');
        }

        $embedding = $query->getVector();
        $limit = 5;

        $sql = sprintf(
            "SELECT id, content, metadata
         FROM %s
         ORDER BY embedding <=> :embedding
         LIMIT :limit",
            $this->table
        );

        $rows = $this->connection
            ->executeQuery($sql, [
                'embedding' => $this->toPgVector($embedding->getData()),
                'limit' => $limit
            ])
            ->fetchAllAssociative();

        foreach ($rows as $row) {
            yield new VectorDocument(
                $row['id'],
                $embedding,
                new Metadata(
                    json_decode($row['metadata'], true) ?? []
                ),
                $row['content']
            );
        }
    }


    public function clear(): void
    {
        $this->connection->executeStatement(
            sprintf("TRUNCATE TABLE %s", $this->table)
        );
    }

    public function remove(string|array $ids, array $options = []): void
    {
        $ids = (array) $ids;

        $this->connection->executeStatement(
            sprintf("DELETE FROM %s WHERE id = ANY(:ids)", $this->table),
            ['ids' => $ids],
            ['ids' => Connection::PARAM_STR_ARRAY]
        );
    }
    private function toPgVector(array $vector): string
    {
        return '[' . implode(',', $vector) . ']';
    }
}