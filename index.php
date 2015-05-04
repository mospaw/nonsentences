<?php

include_once ('nonsentences.php');

$nonsentence = new Nonsentences();

$paragraphs = rand (2, 10);

for ( $x = 0; $x < $paragraphs; $x++ ) {

	$sentences = rand ( 3, 20 );
	echo '<p>' . $nonsentence->sentences( $sentences ) . '</p>' . PHP_EOL;


}


