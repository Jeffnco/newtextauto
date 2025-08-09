<?php
/**
 * Autoloader simple pour remplacer Composer
 */

spl_autoload_register(function ($class) {
    // Convertir le namespace en chemin de fichier
    $prefix = 'ContentFactory\\';
    $base_dir = __DIR__ . '/src/';
    
    // Vérifier si la classe utilise notre namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // Obtenir le nom de classe relatif
    $relative_class = substr($class, $len);
    
    // Remplacer les backslashes par des slashes et ajouter .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // Si le fichier existe, l'inclure
    if (file_exists($file)) {
        require $file;
    }
});