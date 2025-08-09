<?php
namespace ContentFactory\Models;

use ContentFactory\Core\Database;

class Article
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(array $filters = []): array
    {
        $endpoint = $this->db->getTableName('articles');
        
        if (!empty($filters)) {
            $queryParams = [];
            foreach ($filters as $field => $value) {
                if (!empty($value)) {
                    $queryParams[] = "($field,eq," . urlencode($value) . ")";
                }
            }
            if (!empty($queryParams)) {
                $endpoint .= '?where=' . implode(',and,', $queryParams);
            }
        }

        $result = $this->db->makeRequest($endpoint);
        return $result['success'] ? ($result['data']['list'] ?? []) : [];
    }

    public function getById(string $id): ?array
    {
        $result = $this->db->makeRequest($this->db->getTableName('articles') . '/' . urlencode($id));
        return $result['success'] ? $result['data'] : null;
    }

    public function create(array $data): bool
    {
        $result = $this->db->makeRequest($this->db->getTableName('articles'), 'POST', $data);
        return $result['success'];
    }

    public function update(string $id, array $data): bool
    {
        $result = $this->db->makeRequest(
            $this->db->getTableName('articles') . '/' . urlencode($id), 
            'PATCH', 
            $data
        );
        return $result['success'];
    }

    public function delete(string $id): bool
    {
        $result = $this->db->makeRequest(
            $this->db->getTableName('articles') . '/' . urlencode($id), 
            'DELETE'
        );
        return $result['success'];
    }
}