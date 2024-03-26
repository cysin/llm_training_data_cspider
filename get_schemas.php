<?php
// Function to find all SQLite files recursively
function find_sqlite_files($dir)
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

    foreach ($iterator as $file_info) {
        if ($file_info->isFile() && $file_info->getExtension() === 'sqlite') {
            $files[] = $file_info->getPathname();
        }
    }

    return $files;
}

// Function to get table schemas from a SQLite database
function get_table_schemas($db_path)
{
    $db = new SQLite3($db_path);
    $schemas = [];

    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");

    while ($table = $tables->fetchArray()) {
        $table_name = $table['name'];
        $schema = $db->querySingle("SELECT sql FROM sqlite_master WHERE name='$table_name';");
        $schemas[$table_name] = $schema;
    }

    $db->close();
    return $schemas;
}

// Find all SQLite files recursively in the current directory
$sqlite_files = find_sqlite_files(__DIR__);

// Create an empty array to store the database schemas
$database_schemas = [];

// Iterate over each SQLite file and retrieve the table schemas
foreach ($sqlite_files as $file) {
    $database_name = pathinfo($file, PATHINFO_FILENAME);
    $database_schemas[$database_name] = get_table_schemas($file);
}

// Convert the database schemas to JSON and write to a file
$json_data = json_encode($database_schemas, JSON_PRETTY_PRINT);
file_put_contents('database_schemas.json', $json_data);
?>
