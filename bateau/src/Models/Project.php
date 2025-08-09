<?php
namespace ContentFactory\Models;

use ContentFactory\Core\Database;

class Project
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        $result = $this->db->makeRequest($this->db->getTableName('projects'));
        return $result['success'] ? ($result['data']['list'] ?? []) : [];
    }

    public function getById(string $id): ?array
    {
        $result = $this->db->makeRequest($this->db->getTableName('projects') . '/' . urlencode($id));
        return $result['success'] ? $result['data'] : null;
    }

    public function create(array $data): bool
    {
        $result = $this->db->makeRequest($this->db->getTableName('projects'), 'POST', $data);
        return $result['success'];
    }

    public function update(string $id, array $data): bool
    {
        $result = $this->db->makeRequest(
            $this->db->getTableName('projects') . '/' . urlencode($id), 
            'PATCH', 
            $data
        );
        return $result['success'];
    }

    public function delete(string $id): bool
    {
        $result = $this->db->makeRequest(
            $this->db->getTableName('projects') . '/' . urlencode($id), 
            'DELETE'
        );
        return $result['success'];
    }
}