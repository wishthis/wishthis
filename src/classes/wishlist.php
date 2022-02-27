<?php

/**
 * A wishlist.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class Wishlist
{
    private int $id;
    private string $hash;

    public array $data;
    public array $wishes = array();

    public bool $exists = false;

    public function __construct(int|string $id_or_hash)
    {
        global $database;

        $column;

        if (is_int($id_or_hash)) {
            $column = 'id';
        }
        if (is_string($id_or_hash)) {
            $column = 'hash';
            $id_or_hash = '"' . $id_or_hash . '"';
        }

        /**
         * Get Wishlist
         */
        $result = $database
        ->query('SELECT *
                   FROM `wishlists`
                  WHERE `' . $column . '` = ' . $id_or_hash . ';')
        ->fetch();

        $this->data = $result ? $result : array();

        /** Exists */
        if (isset($this->data['id'])) {
            $this->id     = $this->data['id'];
            $this->exists = true;
        } else {
            return;
        }

        /**
         * Get Wishes
         */
        $this->wishes = $database
        ->query('SELECT *
                   FROM `wishes`
                  WHERE `wishlist` = ' . $this->id . ';')
        ->fetchAll();

        foreach ($this->wishes as &$wish) {
            $wish = new Wish($wish);
        }
    }

    public function getCards($options = array()): string
    {
        ob_start();

        /**
         * Options
         */
        $exclude = isset($options['exclude']) ? $options['exclude'] : array();

        if ($exclude) {
            $wishes = array_filter($this->wishes, function ($wish) use ($exclude) {
                return !in_array($wish->status, $exclude);
            });
        } else {
            $wishes = $this->wishes;
        }

        /**
         * Cards
         */
        if (!empty($wishes)) { ?>
            <div class="ui three column doubling stackable grid">
                <?php foreach ($wishes as $wish) { ?>
                    <div class="column">
                        <?= $wish->getCard($this->data['user'], false) ?>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="ui container">
                <div class="sixteen wide column">
                    <?= Page::info('This wishlist seems to be empty.', 'Empty'); ?>
                </div>
            </div>
            <?php
        }

        $html = ob_get_clean();

        return $html;
    }
}
