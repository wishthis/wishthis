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
            $column = 'hash';
        }

        /**
         * Get Wishlist
         */
        $columns = $database
        ->query(
            'SELECT *
               FROM `wishlists`
              WHERE `' . $column . '` = :id_or_hash;',
            array(
                'id_or_hash' => $id_or_hash,
            )
        )
        ->fetch();

        if ($columns) {
            $this->exists = true;

            foreach ($columns as $key => $value) {
                if (is_string($value)) {
                    $this->$key = html_entity_decode($value);
                } else {
                    $this->$key = $value;
                }
            }
        } else {
            return;
        }

        /**
         * Get Wishes
         */
        // $this->wishes = $this->getWishes();
    }

    public function getWishes(array $options = array('placeholders' => array())): array
    {
        global $database;

        if (!isset($options['WHERE'])) {
            $options['placeholders']['wishlist_id'] = $this->id;
        }

        $SELECT    = isset($options['SELECT'])    ? $options['SELECT']    : Wish::SELECT;
        $FROM      = isset($options['FROM'])      ? $options['FROM']      : Wish::FROM;
        $LEFT_JOIN = isset($options['LEFT_JOIN']) ? $options['LEFT_JOIN'] : Wish::LEFT_JOIN;
        $WHERE     = isset($options['WHERE'])     ? $options['WHERE']     : '`wishlist` = :wishlist_id';
        $ORDER_BY  = isset($options['ORDER_BY'])  ? $options['ORDER_BY']  : '`priority` DESC, `url` ASC, `title` ASC';

        /** Default to showing available wishes */
        $wish_status = ' AND (
                `wishes`.`status` IS NULL
            OR (
                    `wishes`.`status` != "' . Wish::STATUS_UNAVAILABLE . '"
                AND `wishes`.`status` != "' . Wish::STATUS_FULFILLED . '"
                AND `wishes`.`status`  < unix_timestamp(CURRENT_TIMESTAMP - INTERVAL ' . Wish::STATUS_TEMPORARY_MINUTES . ' MINUTE)
            )
        )';

        if ($_SESSION['user']->isLoggedIn()) {
            $wishlist_ids = array_map(
                function ($wishlist_data) {
                    return intval($wishlist_data['id']);
                },
                $_SESSION['user']->getWishlists()
            );

            /** Show all wishes (except fulfilled) */
            if (in_array($this->id, $wishlist_ids, true)) {
                $wish_status = ' AND (`wishes`.`status` IS NULL OR `wishes`.`status` != "' . Wish::STATUS_FULFILLED . '")';
            }
        }

        $WHERE .= $wish_status;

        $this->wishes = $database
        ->query(
            '  SELECT ' . $SELECT . '
                FROM ' . $FROM . '
            LEFT JOIN ' . $LEFT_JOIN . '
                WHERE ' . $WHERE . '
            ORDER BY ' . $ORDER_BY . ';',
            $options['placeholders']
        )
        ->fetchAll();

        foreach ($this->wishes as &$wish) {
            $wish = new Wish($wish, false);
        }

        return $this->wishes;
    }

    public function getCards(array $options = array('placeholders' => array())): string
    {
        ob_start();

        /**
         * Options
         */
        if (!empty($options)) {
            $this->wishes = $this->getWishes($options);
        }

        $style = isset($options['style']) ? $options['style'] : 'grid';

        /**
         * Cards
         */
        switch ($style) {
            case 'list':
                ?>
                <div class="ui one column doubling stackable compact grid wishlist">
                    <?php if (!empty($this->wishes)) { ?>
                        <?php foreach ($this->wishes as $wish) { ?>
                            <div class="column">
                                <?php
                                $wish->style = $style;

                                echo $wish->getCard($this->user);
                                ?>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="sixteen wide column">
                            <?= Page::info(__('This wishlist seems to be empty.'), __('Empty')); ?>
                        </div>
                    <?php } ?>
                </div>
                <?php
                break;

            default:
                ?>
                <div class="ui three column doubling stackable compact grid wishlist">
                    <?php if (!empty($this->wishes)) { ?>
                        <?php foreach ($this->wishes as $wish) { ?>
                            <div class="column">
                                <?php
                                $wish->style = $style;

                                echo $wish->getCard($this->user);
                                ?>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="sixteen wide column">
                            <?= Page::info(__('This wishlist seems to be empty.'), __('Empty')); ?>
                        </div>
                    <?php } ?>
                </div>
                <?php
                break;
        }

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
