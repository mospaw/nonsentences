<?php

include_once ('nonsentences.php');

$args = array(
	'min_sentences' => 3,
	'max_sentences' => 20,
	'min_paragraphs' => 2,
	'max_paragraphs' => 10,
	'paragraph_wrapper' => array ( '', "\n\n" ),
);

$nonsentence = new Nonsentences( $args );

echo $nonsentence->paragraphs();
