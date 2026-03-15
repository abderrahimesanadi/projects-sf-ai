<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $product = null;

    #[ORM\Column]
    private ?int $qte = null;

    #[ORM\Column(length: 255)]
    private ?string $customer = null;

    #[ORM\Column]
    private ?\DateTime $order_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $price = null;

    public static function create(string $product, int $qte, string $customer, \DateTime $order_date, ?string $price): self
    {
        $instance = new self();
        $instance->product = $product;
        $instance->qte = $qte;
        $instance->customer = $customer;
        $instance->order_date = $order_date;
        $instance->price = $price;

        return $instance;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): static
    {
        $this->qte = $qte;

        return $this;
    }

    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function setCustomer(string $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getOrderDate(): ?\DateTime
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTime $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }
}
