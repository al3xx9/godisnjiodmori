<?php
interface EntityInterface
{
    public function database_key(): string;
    public function __construct(int $id);
    public function write(): void;
    public function read(): void;
    public function __get(string $name): mixed;
    public function __set(string $name, mixed $value): void;
}
