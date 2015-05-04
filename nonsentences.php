<?php

/**
 * Nonsentences
 *
 * V1.0 - 2015-May-03
 * 
 * A nonsense sentence generator.
 * Copyright 2015 Chris Mospaw
 * 
 * Original code based on Nonsense Generator 2.0.3
 * http://www.jholman.com/scripts/nonsense/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2.1
 * of the License, or (at your option) any later version.
 *
 * See LICENSE.md for the terms of the GNU GPL.
 * 
 * See help.html for installation and usage instructions.
 *
 */

define( "NONSENSE_PATH", realpath(dirname(__FILE__)) . '/db/' ); 

/*
function nonsentence ($numSentences = 1, $type = null) {
	$lists = array("interjections", "determiners", "adjectives", "nouns", "adverbs", "verbs", "prepositions", "conjunctions", "comparatives");
	$vowels = array('a','e','i','o','u');
	
	$type = rand(0,1);
	
	foreach ($lists as $part) ${$part} = file(NONSENSE_PATH."$part.txt");
	
	for ($i=0; $i<2; $i++) {
		foreach ($lists as $part) ${$part}[$i]	= trim(${$part}[rand(0,count(${$part}) - 1)]);
	
		if ($determiners[$i] == "a")
			foreach ($vowels as $vowel)
				if (($type && ($adjectives[$i][0] == $vowel)) || (!$type && ($nouns[$i][0] == $vowel))) $determiners[$i] = "an";
		
	}
	
	$sentence = ($type ?
	ucwords($determiners[0]) . " $adjectives[0] $nouns[0] $adverbs[0] $verbs[0] $prepositions[0] $determiners[1] $adjectives[1] $nouns[1]." :
	"$interjections[0], $determiners[0] $nouns[0] is $comparatives[0] $adjectives[0] than $determiners[1] $adjectives[1] $nouns[1].");
	
	if ($numSentences > 1) return $sentence . " " . nonsentence($numSentences-1);
	return $sentence;
}

function nonsense_word($numWords = 1) {
	$lists = array("interjections", "determiners", "adjectives", "nouns", "adverbs", "verbs", "prepositions", "conjunctions", "comparatives");
	foreach ($lists as $part) $wordlists[] = file(NONSENSE_PATH."$part.txt");
	
	$word_list = '';
	
	for ($count = 1; $count <= $numWords; $count++) {
		if ($count > 1) $word_list .= ' ';
		$list_to_use = mt_rand(0, sizeof($wordlists) - 1);
		$word_to_use = mt_rand(0, sizeof($wordlists[$list_to_use]) - 1);
		
		$word = $wordlists[$list_to_use][$word_to_use];
		
		if (strpos($word, ' ')) {
			$word = substr_replace($word, '', strpos($word, ' '));
		}
		
		$word = trim($word);
		$word_list .= strtolower($word);
	}
	return $word_list;
}
*/


class Nonsentences {

	protected $lists = array( "interjections", "determiners", "adjectives", "nouns", "adverbs", "verbs", "prepositions", "conjunctions", "comparatives" );
	protected $vowels = array( 'a', 'e', 'i', 'o', 'u' );
	protected $wordlists = array();
	protected $punctuation = array( ',', '.', '?', '!' );


	function __construct() {

		$this->output = '';

		foreach ($this->lists as $part) {
			$this->wordlists[$part] = file( NONSENSE_PATH . "$part.txt");
		}

		// Sentence structures ... each is randomly selected. The first tow are weighted since they're a bit more "normal",
		$this->sentence_structures = array (
			split(' ', '[determiners] [adjectives] [nouns] [verbs] [prepositions] [determiners] [nouns] .'),
			split(' ', '[determiners] [adjectives] [nouns] [verbs] [prepositions] [determiners] [nouns] .'),
			split(' ', '[determiners] [adjectives] [nouns] [adverbs] [verbs] [prepositions] [determiners] [adjectives] [nouns] .'),
			split(' ', '[determiners] [adjectives] [nouns] [adverbs] [verbs] [prepositions] [determiners] [adjectives] [nouns] .'),
			split(' ', '[interjections] , [determiners] [nouns] is [comparatives] [adjectives] than [determiners] [adjectives] [nouns] .'),
			split(' ', '[adjectives] [plural_nouns] [verbs] [prepositions] [plural_nouns] [adverbs] .'),
			split(' ', '[determiners] [nouns] , [adverbs] [verbs] the [nouns] .'),
			split(' ', '[interjections] ! The [nouns] [verbs] the [nouns] .'),
		);

//print_r( $this->sentence_structures );

	}

	/**
	 * Get a number of nonsense sentences
	 */
	function sentences( $count = 1 ) {

		$output = '';

		for ( $x=0; $x < $count; $x++ ) {
			$output .= $this->sentence() . ' ';
		}
		return $output;
	}


	/** 
	 * Generate on nonsense sentence
	 */
	function sentence($numSentences = 1) {
		$type = rand( 0, (count($this->sentence_structures) - 1 ) );
		
		for ( $i=0; $i < 2; $i++ ) {
			foreach ($this->lists as $part) {
				${$part}[$i] = trim($this->wordlists[$part][rand(0,count($this->wordlists[$part]) - 1)]);
				$wordcount[$part] = 0;
			}
		
		}
		
		$sentence_structure = $this->sentence_structures[$type];
		$sentence = '';
		foreach ($sentence_structure as $position => $word) {


			switch (1) {

				case ( $position == 0 ) :
					$word = str_replace( array( '[' , ']' ), array ( '', ''), $word );
					$sentence .= ucfirst( ${$word}[ $wordcount[$word] ] );
					$wordcount[$word]++;
				break;

				case ( $word == '[plural_nouns]') :
					$sentence .= $nouns[ $wordcount['nouns'] ] . 's';
					$wordcount['nouns']++;
				break;

				case ( substr($word, 0, 1) == '[') :
					$word = str_replace( array( '[' , ']' ), array ( '', ''), $word );
					$sentence .= ${$word}[ $wordcount[$word] ];
					$wordcount[$word]++;
				break;

				case ( ! in_array( $word, $this->punctuation) ) :
					$sentence .= $word;
				break;

			}

			if ( in_array( $word, $this->punctuation)) {
				$sentence = trim( $sentence ) . $word . ' ';
			}
			else {
				$sentence .= ' ';
			}

		}
		
		//$sentence = trim( $sentence) . '.';

		return $sentence;
	}
	

	function word($numWords = 1) {
		$word_list = '';
		
		for ($count = 1; $count <= $numWords; $count++) {
			if ($count > 1) {
				$word_list .= ' ';
			}
			$list_to_use = rand(0, sizeof($this->wordlists) - 1);
			$word_to_use = rand(0, sizeof($this->wordlists[$this->lists[$list_to_use]]) - 1);
			
			$word = $this->wordlists[$this->lists[$list_to_use]][$word_to_use];
			
			if (strpos($word, ' ')) {
				$word = substr_replace($word, '', strpos($word, ' '));
			}
			
			$word = trim($word);
			$word_list .= strtolower($word);
		}
		$this->output = $word_list;
		return $this->output;
	}
}

