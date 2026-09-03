<?php
/**
 * View Renderer
 */

class View {
    public static function render($viewPath, $data = []) {
        // Extract data array into variables for the view
        extract($data);

        // Normalize view path
        $fullPath = VIEW_PATH . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $viewPath) . '.php';

        if (file_exists($fullPath)) {
            require $fullPath;
        } else {
            die("View not found: " . htmlspecialchars($viewPath));
        }
    }

    public static function partial($partialPath, $data = []) {
        extract($data);
        $fullPath = VIEW_PATH . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $partialPath) . '.php';

        if (file_exists($fullPath)) {
            require $fullPath;
        }
    }
}
