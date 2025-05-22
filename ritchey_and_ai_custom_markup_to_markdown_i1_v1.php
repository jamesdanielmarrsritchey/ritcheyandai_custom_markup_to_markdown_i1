<?php
# Meta
/*
Name: Ritchey&AI Custom Markup To Markdown i1 v1
Description: Convert text (marked using a custom markup language) to Markdown. Returns "TRUE" on success. Returns "FALSE" on failure.
*/
# Content
if (function_exists('ritchey_and_ai_custom_markup_to_markdown_i1_v1') === FALSE){
function ritchey_and_ai_custom_markup_to_markdown_i1_v1($source_file, $destination_file, $overwrite = NULL, $display_errors = NULL){
	$errors = array();
	$location = realpath(dirname(__FILE__));
	if (@is_file($source_file) === FALSE){
		$errors[] = "source_file";
	}
	if (@is_dir(@dirname($destination_file)) === FALSE){
		$errors[] = 'destination_file';
	} else if (@is_file($destination_file) !== FALSE){
		if ($overwrite !== TRUE){
			$errors[] = "destination_file";
		}
	}
	if ($overwrite === NULL){
		$overwrite = FALSE;
	} else if ($overwrite === TRUE){
		// Do nothing
	} else if ($overwrite === FALSE){
		// Do nothing
	} else {
		$errors[] = "overwrite";
	}
	if ($display_errors === NULL){
		$display_errors = FALSE;
	} else if ($display_errors === TRUE){
		// Do nothing
	} else if ($display_errors === FALSE){
		// Do nothing
	} else {
		$errors[] = "display_errors";
	}
	## Task
	if (@empty($errors) === TRUE){
		### Import text as an array of individual lines
		$data = array();
		$handle = @fopen($source_file, 'r');
		while (@feof($handle) !== TRUE) {
			// Get line from file
			$line = @fgets($handle);
			$line = rtrim($line, "\n\r\v");
			$data[] = $line;
		}
		@fclose($handle);
		$data = implode(PHP_EOL, $data);
		$data = str_replace("\r\n", "\n", $data);
		$data_markdown = $data;
		### Use regex to convert items to Markdown
		// Headings
		$data_markdown = preg_replace('/^###### (.*)$/m', '###### $1', $data_markdown);
		$data_markdown = preg_replace('/^##### (.*)$/m', '##### $1', $data_markdown);
		$data_markdown = preg_replace('/^#### (.*)$/m', '#### $1', $data_markdown);
		$data_markdown = preg_replace('/^### (.*)$/m', '### $1', $data_markdown);
		$data_markdown = preg_replace('/^## (.*)$/m', '## $1', $data_markdown);
		$data_markdown = preg_replace('/^# (.*)$/m', '# $1', $data_markdown);
		// Comments
		$data_markdown = preg_replace('/^\/\/.*$/m', '', $data_markdown);
		// Block Quotes
		$data_markdown = preg_replace_callback('/^"\n(.*?)\n"/ms', function ($m) {
    		return preg_replace('/^/m', '> ', trim($m[1]));
		}, $data_markdown);
		// Block Message
		$data_markdown = preg_replace_callback('/^=\n(.*?)\n=/ms', function ($m) {
   		 return "```\n" . trim($m[1]) . "\n```";
		}, $data_markdown);
		// Labels
		$data_markdown = preg_replace('/^([ \t]*)(- )?([A-Za-z0-9 \-]+):(?: (.*))?$/m', '$1$2**$3:**$4', $data_markdown);
		// Lists
		$data_markdown = preg_replace('/^([ ]*)- (.*)$/m', '${1}- $2', $data_markdown);
		$data_markdown = preg_replace('/^([ \t]*)([A-Za-z0-9 \-]+): (.+)$/m', '$1**$2:** $3', $data_markdown);
		// Images
		$data_markdown = preg_replace('/\((https?:\/\/[^\s()]+?\.(png|jpg|webp))\)/', '![]($1)', $data_markdown);
		$data_markdown = preg_replace('/\((\/[^\s()]+?\.(png|jpg|webp))\)/', '![]($1)', $data_markdown);
		$data_markdown = preg_replace('/\(([^\s()]+?\.(png|jpg|webp))\)/', '![]($1)', $data_markdown);
		// Hyperlinks
		$data_markdown = preg_replace('/\{(.+?)\} \((https?:\/\/[^\s()]+)\)/', '[$1]($2)', $data_markdown);
		// Tables


function convert_custom_markup_to_markdown($text) {
    $lines = preg_split('/\r\n|\r|\n/', $text);
    $output = [];
    $buffer = [];
    $inTable = false;
    $label = null;

    foreach ($lines as $line) {
        $trimmed = trim($line);

        // Handle label lines like "SOMETHING:"
        if (!$inTable && substr($trimmed, -1) === ':' && strpos($trimmed, '|') === false) {
            $label = rtrim($trimmed, ':');
            continue;
        }

        // Handle table/flat-list lines (must contain |)
        if (strpos($trimmed, '|') !== false) {
            if (!$inTable) {
                $inTable = true;
                $buffer = [];
            }
            $buffer[] = $trimmed;
            continue;
        }

        // Handle internal row separator '---' lines
        if ($inTable && $trimmed === '---') {
            continue;
        }

        // If ending a table block, flush it
        if ($inTable) {
            $output = array_merge($output, flush_table_block($label, $buffer));
            $label = null;
            $buffer = [];
            $inTable = false;
        }

        // Handle horizontal rule `--`
        if ($trimmed === '--') {
            $output[] = '---';
            continue;
        }

        // Regular line
        $output[] = $line;
    }

    // Final flush if ending with a table
    if ($inTable) {
        $output = array_merge($output, flush_table_block($label, $buffer));
    }

    return implode("\n", $output);
}

function flush_table_block($label, $lines) {
    $out = [];

    if ($label !== null) {
        $out[] = "**$label:**";
    }

    // Normalize rows
    $rows = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') continue;
        if (substr($line, 0, 1) !== '|') $line = '|' . $line;
        if (substr($line, -1) !== '|') $line .= '|';
        $rows[] = $line;
    }

    if (count($rows) === 0) return [];

    $colCount = substr_count($rows[0], '|') - 1;
    $separator = '|' . implode('|', array_fill(0, $colCount, '---')) . '|';

    $out[] = $rows[0];
    $out[] = $separator;
    $out = array_merge($out, array_slice($rows, 1));

    // Add a trailing blank line for spacing
    $out[] = '';

    return $out;
}
$data_markdown = convert_custom_markup_to_markdown($data_markdown);
		// Horizontal Rules
		$data_markdown = preg_replace('/^--$/m', '---', $data_markdown);
		// Flat-Lists (treated as tables)
		// Handled already
		// Bold, Italic, Underline, Strikethrough
		$data_markdown = preg_replace('/\{(.+?)\} \(Bold\)/i', '**$1**', $data_markdown);
		$data_markdown = preg_replace('/\{(.+?)\} \(Italic\)/i', '*$1*', $data_markdown);
		$data_markdown = preg_replace('/\{(.+?)\} \(Underline\)/i', '<u>$1</u>', $data_markdown);
		$data_markdown = preg_replace('/\{(.+?)\} \(Strikethrough\)/i', '~~$1~~', $data_markdown);
		### Write data to file
		file_put_contents($destination_file, $data_markdown);
	}
	result:
	## Display Errors
	if ($display_errors === TRUE){
		if (@empty($errors) === FALSE){
			$message = @implode(", ", $errors);
			if (function_exists('ritchey_and_ai_custom_markup_to_markdown_i1_v1_format_error') === FALSE){
				function ritchey_and_ai_custom_markup_to_markdown_i1_v1_format_error($errno, $errstr){
					echo $errstr;
				}
			}
			set_error_handler("ritchey_and_ai_custom_markup_to_markdown_i1_v1_format_error");
			trigger_error($message, E_USER_ERROR);
		}
	}
	## Return
	if (@empty($errors) === TRUE){
		return TRUE;
	} else {
		return FALSE;
	}
}
}
?>