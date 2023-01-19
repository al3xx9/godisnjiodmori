<?php
class Database
{
    private static $instance = null;
    private string $db_file_path;
    private array $database;

    private function __construct(string $db_file_path = 'data/database.json')
    {
        $this->db_file_path = $db_file_path;
        $this->read();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function read(): void
    {
        if (empty($this->db_file_path) || !is_file($this->db_file_path)) {
            throw new Exception('DB file path incorectly set in constructor!');
        }
        $contents = file_get_contents($this->db_file_path);
        $data = json_decode($contents, true);
        if (!is_array($data)) {
            throw new Exception('Database file is corrupted!');
        }
        $this->database = $data;
    }

    public function write(): void
    {
        if (empty($this->db_file_path) || !is_file($this->db_file_path)) {
            throw new Exception('DB file path incorectly set in constructor!');
        }
        file_put_contents($this->db_file_path, json_encode($this->database, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    public function get(): array
    {
        return $this->database;
    }

    public function set(array $database): void
    {
        $this->database = $database;
        $this->write();
    }
}
