<?php
# App.php
$location = realpath(dirname(__FILE__));
require_once $location . '/ritchey_and_ai_custom_markup_to_markdown_i1_v1.php';
$return = ritchey_and_ai_custom_markup_to_markdown_i1_v1("{$location}/temporary/source.txt", "{$location}/temporary/destination.md", TRUE, TRUE);
if ($return === TRUE){
	echo "TRUE" . PHP_EOL;
} else {
	echo "FALSE" . PHP_EOL;
}
?>