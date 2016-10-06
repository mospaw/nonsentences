# nonsentences

A revival of Jeff Holman's nonsense generator. Original script: http://www.jholman.com/scripts/nonsense/ (note that there are reports of malware on this site and it might not be the original site any more).

This project is still in very early development. In other words, the code isn't fully converted, optimized, 
or even pretty. The sentence and title generators reply on the word lists in the `db` folder. 

See `example.php` for an example of use and some configuration and how to use it outside of WP-CLI.

# WP-CLI usage

`wp --require=nonsentences.php nonsentences generate_posts`

## Parameters and defaults

```
'min_sentences' => 3,
'max_sentences' => 20,
'min_paragraphs' => 2,
'max_paragraphs' => 10,
'post_type' => 'post',
'number_of_posts' => 10,
```

`post_type` is currently not checked for validity. 

The number of sentences in a paragraph is a random number between the two relevant parameters.

The number of paragraphs per post is a random number between the two relevant parameters.

# Todo

- Add additional [WP-CLI](http://wp-cli.org/) support to generate a post or posts with generated content.
- Document options for code-based usage.
- Restructure as WordPress plugin that includes WP-CLI functionality and wp-admin

# Changes

## V1.3

- Initial work to add WP-CLI integration via `generate_posts`
- Rename `index.php` to `example.php`
- Add `.gitignore` to allow a WordPress installation in `wp/` folder and WordPress `index.php` and `wp-config.php` in root

## V1.2

- Abstract word list generator into wrappable `get_words()` method
- Change `sentence()` method to wrap `get_words()`
- Add support for titles, including word list structure, using `get_words()`
- Add logic to add WP-CLI support in main file
- Add stub for WP-CLI support
- Add additional "normal" sentence structure duplicates

## V1.1

- Add paragraphs output
- Add passed parameters to control the number of paragraphs, sentences, and a paragraph wrapper.
- Removed dead code

## V1.0

- Imported latest known version of "nonsense generator"
- Edits to word lists
- Added new sentence structures
- Code refactoring for PHP5
