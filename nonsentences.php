<?php
/**
 * Nonsentences
 *
 * V1.1 - 2015-May-08
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

define( "NONSENSE_PATH", realpath( dirname( __FILE__ ) ) . '/db/' ); 

class Nonsentences {

	protected $lists = array( "interjections", "determiners", "adjectives", "nouns", "adverbs", "verbs", "prepositions", "conjunctions", "comparatives" );
	protected $vowels = array( 'a', 'e', 'i', 'o', 'u' );
	protected $wordlists = array();
	protected $punctuation = array( ',', '.', '?', '!' );

	public $min_sentences = 3;
	public $max_sentences;
	public $min_paragraphs = 3;
	public $max_paragraphs;
	public $paragraph_wrapper = array ( '<p>', '</p>' );

	function __construct( $args ) {

		if ( isset ( $args['min_sentences'] ) ) {
			$this->min_sentences = $args['min_sentences'];
		}

		if ( isset ( $args['max_sentences'] ) ) {
			$this->max_sentences = $args['max_sentences'];
		}

		if ( isset ( $args['min_paragraphs'] ) ) {
			$this->min_paragraphs = $args['min_paragraphs'];
		}

		if ( isset ( $args['max_paragraphs'] ) ) {
			$this->max_paragraphs = $args['max_paragraphs'];
		}

		if ( isset ( $args['paragraph_wrapper'] ) ) {
			$this->paragraph_wrapper = $args['paragraph_wrapper'];
		}

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
	function sentences( ) {

		if ( $this->max_sentences === null ) {
			$this->max_sentences = $this->min_sentences;
		}

		$count = rand ( $this->min_sentences, $this->max_sentences );

		$output = '';

		for ( $x=0; $x < $count; $x++ ) {
			$output .= $this->sentence() . ' ';
		}
		return $output;

	}


	function paragraphs( ) {

		if ( $this->max_paragraphs === null ) {
			$this->max_paragraphs = $this->min_paragraphs;
		}

		$count = rand ( $this->min_paragraphs, $this->max_paragraphs );

		$output = '';

		for ( $x=0; $x < $count; $x++ ) {
			$output .= $this->paragraph_wrapper[0];
			$output .= $this->sentences() . ' ';
			$output .= $this->paragraph_wrapper[1];
		}

		return $output . PHP_EOL;		

	}


	/** 
	 * Generate one nonsense sentence
	 */
	function sentence() {
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
	

//	function word($numWords = 1) {
//		$word_list = '';
//		
//		for ($count = 1; $count <= $numWords; $count++) {
//			if ($count > 1) {
//				$word_list .= ' ';
//			}
//			$list_to_use = rand(0, sizeof($this->wordlists) - 1);
//			$word_to_use = rand(0, sizeof($this->wordlists[$this->lists[$list_to_use]]) - 1);
//			
//			$word = $this->wordlists[$this->lists[$list_to_use]][$word_to_use];
//			
//			if (strpos($word, ' ')) {
//				$word = substr_replace($word, '', strpos($word, ' '));
//			}
//			
//			$word = trim($word);
//			$word_list .= strtolower($word);
//		}
//		$this->output = $word_list;
//		return $this->output;
//	}
}

