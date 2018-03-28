<?php

include_once('nonsentences.php');

$args = array(
    'min_sentences' => 3,
    'max_sentences' => 20,
    'min_paragraphs' => 2,
    'max_paragraphs' => 10,
);

$nonsentence = new Nonsentences($args);

echo '<h1>' . $nonsentence->title() . '</h1>' . PHP_EOL;
echo $nonsentence->paragraphs();
