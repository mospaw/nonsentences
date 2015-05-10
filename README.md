# nonsentences

A revival of Jeff Holman's nonsense generator. Original script: http://www.jholman.com/scripts/nonsense/

This project is still in very early development. In other words, the code isn't fully converted, optimized, 
or even pretty. The sentence and title generators reply on the word lists in the `db` folder. 

See `index.php` for an example of use and some configuration

# Todo

- Add [WP-CLI](http://wp-cli.org/) support to generate a post or posts with generated content.
- Document options for code-based usage.

# Changes

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
