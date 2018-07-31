<?php
/**
 * Nonsentences
 *
 * V1.4 - 2018-Jul-30
 *
 * A nonsense sentence and title generator.
 * Copyright 2015-2018 Chris Mospaw
 *
 * Original code based on Nonsense Generator 2.0.3
 * http://www.jholman.com/scripts/nonsense/ (URL now defunct)
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2.1
 * of the License, or (at your option) any later version.
 *
 * See LICENSE.md for the terms of the GNU GPL.
 *
 * See README.md / example.php for installation and usage instructions.
 *
 */

// Add WP-CLI support if needed.
if (defined('WP_CLI') && WP_CLI) {
    include_once __DIR__ . '/wp-cli.php';
}

class Nonsentences
{
    protected $lists = array( "interjections", "determiners", "adjectives", "nouns", "adverbs", "verbs", "prepositions", "conjunctions", "comparatives" );
    protected $vowels = array( 'a', 'e', 'i', 'o', 'u' );
    protected $wordlists = array();
    protected $punctuation = array( ',', '.', '?', '!' );
    protected $sentence_structures;
    protected $title_structures;

    public $min_sentences = 3;
    public $max_sentences;
    public $min_paragraphs = 3;
    public $max_paragraphs;
    public $paragraph_wrapper = array( '<p>', '</p>' );
    public $taxonomy;
    public $taxonomy_term;

    // Used by WP-CLI
    public $number_of_posts;
    public $post_type;

    public function __construct($args)
    {
        if (isset($args['min_sentences'])) {
            $this->min_sentences = $args['min_sentences'];
        }

        if (isset($args['max_sentences'])) {
            $this->max_sentences = $args['max_sentences'];
        }

        if (isset($args['min_paragraphs'])) {
            $this->min_paragraphs = $args['min_paragraphs'];
        }

        if (isset($args['max_paragraphs'])) {
            $this->max_paragraphs = $args['max_paragraphs'];
        }

        if (isset($args['number_of_posts'])) {
            $this->number_of_posts = $args['number_of_posts'];
        }

        if (isset($args['post_type'])) {
            $this->post_type = $args['post_type'];
        }

        if (isset($args['paragraph_wrapper'])) {
            $this->paragraph_wrapper = $args['paragraph_wrapper'];
        }

        if (isset($args['taxonomy'])) {
            $this->taxonomy = $args['taxonomy'];
        }

        if (isset($args['taxonomy_term'])) {
            $this->taxonomy_term = $args['taxonomy_term'];
        }

        
        foreach ($this->lists as $part) {
            $this->wordlists[$part] = file(realpath(dirname(__FILE__)) . '/db/' . $part . '.txt');
        }

        // Sentence structures ... each is randomly selected. The first two are weighted since they're a bit more "normal",
        $this->sentence_structures = array(
            explode(' ', '[determiners] [adjectives] [nouns] [verbs] [prepositions] [determiners] [nouns] .'),
            explode(' ', '[determiners] [adjectives] [nouns] [verbs] [prepositions] [determiners] [nouns] .'),
            explode(' ', '[determiners] [adjectives] [nouns] [verbs] [prepositions] [determiners] [nouns] .'),
            explode(' ', '[determiners] [adjectives] [nouns] [adverbs] [verbs] [prepositions] [determiners] [adjectives] [nouns] .'),
            explode(' ', '[determiners] [adjectives] [nouns] [adverbs] [verbs] [prepositions] [determiners] [adjectives] [nouns] .'),
            explode(' ', '[determiners] [adjectives] [nouns] [adverbs] [verbs] [prepositions] [determiners] [adjectives] [nouns] .'),
            explode(' ', '[interjections] , [determiners] [nouns] is [comparatives] [adjectives] than [determiners] [adjectives] [nouns] .'),
            explode(' ', '[adjectives] [plural_nouns] [verbs] [prepositions] [plural_nouns] [adverbs] .'),
            explode(' ', '[determiners] [nouns] , [adverbs] [verbs] the [nouns] .'),
            explode(' ', '[interjections] ! The [nouns] [verbs] the [nouns] .'),
        );


        // Title structures ... each is randomly selected.
        $this->title_structures = array(
            explode(' ', '[determiners] [nouns] [adverbs] [verbs] [determiners] [nouns]'),
            explode(' ', '[determiners] [nouns] [verbs]'),
            explode(' ', '[adverbs] [verbs] [nouns]'),
            explode(' ', '[interjections] , [determiners] [nouns] [verbs]'),
            explode(' ', '[adjectives] [nouns]'),
        );
    }

    /**
     * Get a number of nonsense sentences
     */
    public function sentences()
    {
        if ($this->max_sentences === null) {
            $this->max_sentences = $this->min_sentences;
        }

        $count = rand($this->min_sentences, $this->max_sentences);

        $output = '';

        for ($x=0; $x < $count; $x++) {
            $output .= $this->sentence() . ' ';
        }
        return $output;
    }


    /**
     * Return a number of paragraphs of assembled from random sentences, wrapped in the
     * configured wrapper.
     */
    public function paragraphs()
    {
        if ($this->max_paragraphs === null) {
            $this->max_paragraphs = $this->min_paragraphs;
        }

        $count = rand($this->min_paragraphs, $this->max_paragraphs);

        $output = '';

        for ($x=0; $x < $count; $x++) {
            $output .= $this->paragraph_wrapper[0];
            $output .= $this->sentences() . ' ';
            $output .= $this->paragraph_wrapper[1];
        }

        return $output . PHP_EOL;
    }


    /**
     * Generate one nonsense sentence
     */
    public function sentence()
    {
        return $this->get_words('sentence_structures');
    }

    /**
     * Generate one nonsense title
     */
    public function title()
    {
        return $this->get_words('title_structures');
    }

    /**
     * Get a list of words assembled from ramdom words in the lists as determined by the
     * passed structure type.
     */
    public function get_words($structure_type)
    {
        $wordcount = array();

        $type = rand(0, (count($this->{$structure_type}) - 1));

        for ($i=0; $i < 2; $i++) {
            foreach ($this->lists as $part) {
                ${$part}[$i] = trim($this->wordlists[$part][rand(0, count($this->wordlists[$part]) - 1)]);
                $wordcount[$part] = 0;
            }
        }
   
        $sentence_structure = $this->{$structure_type}[$type];
        $word_list = '';
        foreach ($sentence_structure as $position => $word) {
            switch (1) {

                case ($position == 0):
                    $word = str_replace(array( '[' , ']' ), array( '', ''), $word);
                    $word_list .= ucfirst(${$word}[ $wordcount[$word] ]);
                    $wordcount[ $word ]++;
                break;

                case ($word == '[plural_nouns]'):
                    $word_list .= $nouns[ $wordcount['nouns'] ] . 's';
                    $wordcount['nouns']++;
                break;

                case (substr($word, 0, 1) == '['):
                    $word = str_replace(array( '[' , ']' ), array( '', ''), $word);
                    $word_list .= ${$word}[ $wordcount[$word] ];
                    $wordcount[ $word ]++;
                break;

                case (! in_array($word, $this->punctuation)):
                    $word_list .= $word;
                break;

            }

            if (in_array($word, $this->punctuation)) {
                $word_list = trim($word_list) . $word . ' ';
            } else {
                $word_list .= ' ';
            }
        }
        
        return $word_list;
    }
}
