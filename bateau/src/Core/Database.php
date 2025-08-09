<?php
namespace ContentFactory\Core;

class Database
{
    private static $instance = null;
    private $config;

    private function __construct()
    {
        $this->config = require __DIR__ . '/../../config/database.php';
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function makeRequest(string $endpoint, string $method = 'GET', array $data = null): array
    {
        $url = $this->config['nocodb']['base_url'] . $this->config['nocodb']['project'] . '/' . $endpoint;
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'xc-token: ' . $this->config['nocodb']['token'],
                'Content-Type: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30
        ]);

        if ($data && in_array($method, ['POST', 'PATCH', 'PUT'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        return [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'data' => json_decode($response, true),
            'http_code' => $httpCode,
            'error' => $error
        ];
    }

    public function getTableName(string $alias): string
    {
        return $this->config['tables'][$alias] ?? $alias;
    }
}