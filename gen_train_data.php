<?php
// Load the JSON files
$train_data = json_decode(file_get_contents('train.json'), true);
$database_schemas = json_decode(file_get_contents('database_schemas.json'), true);

// Array to store the output
$output_array = [];

// Loop through each item in the train.json array
foreach ($train_data as $item) {
    // Get the database schema for the corresponding db_id
    $db_schema = $database_schemas[$item['db_id']];

    // Create the output array
    $output = [
        'instruction' => $db_schema,
        'input' => $item['question'],
        'output' => $item['query'],
        'system' => '',
        'history' => []
    ];

    // Add the output to the output array
    $output_array[] = $output;
}

// Encode the output array to JSON without Unicode characters and with proper formatting
$json_output = json_encode($output_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// Write the output to a new JSON file
file_put_contents('train_sql_cspider.json', $json_output);
?>
