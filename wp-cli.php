<?php
/**
 * Implements Nonsentences WP-CLI command
 */
class Nonsentences_Command extends WP_CLI_Command
{
    private $nonsentences;
    private $args;


    private function get_nonsentences($args, $assoc_args)
    {
        $defaults = array(
            'min_sentences' => 3,
            'max_sentences' => 20,
            'min_paragraphs' => 2,
            'max_paragraphs' => 10,
            'post_type' => 'post',
            'number_of_posts' => 10,
        );

        $args = array_merge($defaults, $assoc_args);

        return new Nonsentences($args);
    }


    /**
     * Prints a greeting.
     */
    public function generate_posts($args, $assoc_args)
    {
        $this->nonsentences = $this->get_nonsentences($args, $assoc_args);

        $s = '';
        if ($this->nonsentences->number_of_posts > 1) {
            $s = 's';
        }

        for ($x = 0; $x < $this->nonsentences->number_of_posts; $x++) {
            wp_insert_post(
                array(
                'post_title' => $this->nonsentences->title(),
                'post_content' =>  $this->nonsentences->paragraphs(),
                'post_status' => 'publish',
                'post_type' => $this->nonsentences->post_type,
                )
            );
        }

        WP_CLI::line('Inserted ' .  $this->nonsentences->number_of_posts . ' ' . $this->nonsentences->post_type . $s . '.');
    }
}

WP_CLI::add_command('nonsentences', 'Nonsentences_Command');
