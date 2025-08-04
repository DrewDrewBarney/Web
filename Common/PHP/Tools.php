<?php

class Tools {

    static function gp(string $key): mixed {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        } else if (isset($_POST[$key])) {
            return $_POST[$key];
        } else {
            return '';
        }
    }

    static function getFiles(array $roots, string $extension, bool $clientView = false): array {
        $files = [];
        foreach ($roots as $root) {
            $directories = new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS);
            $iterator = new RecursiveIteratorIterator($directories);
            $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === $extension) {
                    $serverPath = $file->getRealPath();
                    $relativePath = str_replace($docRoot, '', $serverPath);
                    $files[] = $clientView ? $relativePath : $serverPath;
                }
            }
        }
        return $files;
    }
}
