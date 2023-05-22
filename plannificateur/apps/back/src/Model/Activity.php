<?php

namespace App\Model;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class Activity
{
    private Uuid $id;

    private function __construct(
        private string $name,
        private string $description,
        private DateTimeImmutable $duration,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    )
    {
        $this->id = Uuid::v4();
    }

    public static function create(
        string $name,
        string $description,
        DateTimeImmutable $duration,
    ): self
    {
        return new self($name, $description, $duration, new DateTimeImmutable(), new DateTimeImmutable());
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDuration(): DateTimeImmutable
    {
        return $this->duration;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}