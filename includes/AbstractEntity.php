<?php
abstract class AbstractEntity implements EntityInterface
{
    protected int $id;
    private Database $database;
    private array $data;

    abstract public function database_key(): string;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->database = Database::getInstance();
        $this->read();
    }

    public function write(): void
    {
        $this->database->write();
    }

    public function read(): void
    {
        $this->database->read();
        $this->data = (array) $this->database->get()[$this->database_key()][$this->id];
    }

    public function __get(string $name): mixed
    {
        if ($name == 'id') {
            return $this->id;
        }
        return $this->data[$name] ?? null;
    }

    public function __set(string $name, mixed $value): void
    {
        $this->data[$name] = $value;
        $this->write();
    }
}
