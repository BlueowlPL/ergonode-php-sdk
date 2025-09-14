<?php

// Load environment variables from .env.test file
if (file_exists(dirname(__DIR__) . '/.tests')) {
    $lines = file(dirname(__DIR__) . '/.tests', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse name and value
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, "'\" ");

        // Set the environment variable if not already set
        if (!getenv($name)) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}
