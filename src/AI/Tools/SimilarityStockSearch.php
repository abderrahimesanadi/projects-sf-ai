<?php


namespace App\AI\Tools;

use Symfony\AI\Agent\Toolbox\Attribute\AsTool;
use Symfony\AI\Store\Document\VectorDocument;
use Symfony\AI\Store\Document\Vectorizer;
use Symfony\AI\Store\Query\VectorQuery;
use App\AI\Stores\PostgreStore;


#[AsTool('similarity_stock_search', description: 'Searches for documents similar to a query or sentence.')]
final class SimilarityStockSearch
{
    /**
     * @var VectorDocument[]
     */
    public array $usedDocuments = [];

    public function __construct(
        private readonly Vectorizer $vectorizer,
        private readonly PostgreStore $store) {
    }

    /**
     * @param string $searchTerm string used for similarity search
     */
    public function __invoke(string $searchTerm): string
    {
        $vector = $this->vectorizer->vectorize($searchTerm);

        $this->usedDocuments = iterator_to_array($this->store->query(new VectorQuery($vector)));

        if ([] === $this->usedDocuments) {
            return 'No results found';
        }

        $result = 'Found documents with following information:'.\PHP_EOL;
        foreach ($this->usedDocuments as $document) {
            $result .= json_encode($document->getMetadata());
        }


        return $result;
    }
}
