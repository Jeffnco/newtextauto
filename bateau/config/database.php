<?php
/**
 * Configuration de la base de données NocoDB
 */
return [
    'nocodb' => [
        'base_url' => 'https://nocodb.inonobu.fr/api/v1/db/data/v1/',
        'api_v2_url' => 'https://nocodb.inonobu.fr/api/v2/tables/',
        'token' => 'OHziyF3fHJQjV6LmVmy9Pf--u7Ai7x6FzrHATo47',
        'project' => 'D4SEO_KEYWORD_CLUSTER_ARTICLE'
    ],
    'tables' => [
        'articles' => 'Article_ecrit',
        'projects' => 'Projets',
        'personas' => 'personas',
        'users' => 'users',
        'rss_feeds' => 'Flux_rss',
        'rss_articles' => 'Article_from_rss',
        'keyword_ideas' => 'Content_Ideas_from_Keywords',
        'cluster_ideas' => 'Content_Ideas_from_Clusters'
    ]
];