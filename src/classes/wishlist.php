<?php

/**
 * A wishlist.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class Wishlist
{
    public array $wishes = array();

    public bool $exists = false;

    public function __construct(int|string $id_or_hash)
    {
        global $database;

        $column;

        if (is_numeric($id_or_hash)) {
            $column = 'id';
        } elseif (is_string($id_or_hash)) {
            $column     = 'hash';
            $id_or_hash = '"' . $id_or_hash . '"';
        }

        /**
         * Get Wishlist
         */
        $columns = $database
        ->query('SELECT *
                   FROM `wishlists`
                  WHERE `' . $column . '` = ' . $id_or_hash . ';')
        ->fetch();

        if ($columns) {
            $this->exists = true;

            foreach ($columns as $key => $value) {
                $this->$key = $value;
            }
        } else {
            return;
        }

        /**
         * Get Wishes
         */
        $this->wishes = $this->getWishes();
    }

    private function getWishes($sql = array()): array
    {
        global $database;

        $SELECT   = isset($sql['SELECT'])   ? $sql['SELECT']   : '*';
        $FROM     = isset($sql['FROM'])     ? $sql['FROM']     : '`wishes`';
        $WHERE    = isset($sql['WHERE'])    ? $sql['WHERE']    : '`wishlist` = ' . $this->id;
        $ORDER_BY = isset($sql['ORDER_BY']) ? $sql['ORDER_BY'] : '`priority` DESC, `title` ASC, `url` ASC';

        $this->wishes = $database
        ->query('SELECT ' . $SELECT . '
                   FROM ' . $FROM . '
                  WHERE ' . $WHERE . '
               ORDER BY ' . $ORDER_BY . ';')
        ->fetchAll();

        foreach ($this->wishes as &$wish) {
            $wish = new Wish($wish, false);
        }

        return $this->wishes;
    }

    public function getCards($options = array()): string
    {
        ob_start();

        /**
         * Options
         */
        if (!empty($options)) {
            $this->wishes = $this->getWishes($options);
        }

        /**
         * Cards
         */
        ?>
        <div class="ui three column doubling stackable grid">
            <?php if (!empty($this->wishes)) { ?>
                <?php foreach ($this->wishes as $wish) { ?>
                    <div class="column">
                        <?= $wish->getCard($this->user) ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="sixteen wide column">
                    <?= Page::info('This wishlist seems to be empty.', 'Empty'); ?>
                </div>
            <?php } ?>
        </div>
        <?php

        $html = ob_get_clean();

        return $html;
    }

    public function getTitle(): string
    {
        $title = __('Wishlist not found');

        if ($this->exists) {
            $title = $this->name;
        }

        return $title;
    }
}
