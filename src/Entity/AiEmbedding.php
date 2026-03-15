<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AiEmbedding
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private string $id;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'json')]
    private array $metadata = [];

    #[ORM\Column(type: 'vector', length: 1024)]
    private array $embedding;

    public static function create(string $content, array $metadata, array $embedding): self
    {
        $instance = new self();
        $instance->id = uuid_create(UUID_TYPE_RANDOM);
        $instance->content = $content;
        $instance->metadata = $metadata;
        $instance->embedding = $embedding;

        return $instance;
    }   
}