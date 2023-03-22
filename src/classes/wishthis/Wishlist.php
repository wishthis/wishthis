<?php

/**
 * A wishlist.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class Wishlist
{
    /**
     * Creates a new wishlist for the user.
     *
     * Returns the wishlist ID or false on failure.
     *
     * @param Database $database MySQL database to use.
     * @param string   $name     Name of the wishlist to create.
     * @param int      $user_id  User (ID) to create the wishlist for.
     *
     * @return int|false
     */
    public static function create(string $name, int $user_id = 0): int|false
    {
        $database = Wishthis::getDatabase();

        if (0 === $user_id) {
            if (isset($_SESSION['user']->id)) {
                $user_id = $_SESSION['user']->id;
            } else {
                new \RuntimeException('User ID could not be determined automatically, please specify the ID.');
            }
        }

        $user = User::getFromID($user_id);

        if (false === $user) {
            return false;
        }

        $name = Sanitiser::getTitle($name);
        $hash = sha1(time() . $user_id . $name);

        $create_wishlist = $database->query(
            'INSERT INTO `wishlists` (
                `user`,
                `name`,
                `hash`
            ) VALUES (
                :user_id,
                :wishlist_name,
                :wishlist_hash
            );',
            array(
                'user_id'       => $user_id,
                'wishlist_name' => $name,
                'wishlist_hash' => $hash,
            )
        );

        if (false !== $create_wishlist) {
            return $database->lastInsertId();
        }

        return false;
    }

    /**
     * Renames a wishlist.
     *
     * Returns true when the specified wishlist was renamed. Returns false on
     * failure or when the wishlist was not renamed, because it already has the
     * specified name.
     *
     * @param string  $name        The new name for the wishlist.
     * @param integer $wishlist_id ID of the wishlist to rename.
     *
     * @return boolean
     */
    public static function rename(string $name, int $wishlist_id): bool
    {
        $database = Wishthis::getDatabase();

        $result = $database
        ->query(
            'UPDATE `wishlists`
                SET `name` = :wishlist_name
              WHERE `id`   = :wishlist_id',
            array(
                'wishlist_name' => Sanitiser::getTitle($name),
                'wishlist_id'   => Sanitiser::getNumber($wishlist_id),
            )
        );


        if (false === $result || 0 === $result->rowCount()) {
            return false;
        }

        return true;
    }

    /**
     * Deletes a wishlist.
     *
     * Returns whether deleting the wishlist was successful.
     *
     * @param integer $wishlist_id The ID of the wishlist to deletet.
     *
     * @return boolean
     */
    public static function delete(int $wishlist_id): bool
    {
        $database = Wishthis::getDatabase();

        $result = $database->query(
            'DELETE FROM `wishlists`
                   WHERE `id` = :wishlist_id;',
            array(
                'wishlist_id' => Sanitiser::getNumber($wishlist_id),
            )
        );

        if (false === $result || 0 === $result->rowCount()) {
            return false;
        }

        return true;
    }

    public array $wishes = array();

    public bool $exists = false;

    public function __construct(int|string $id_or_hash)
    {
        $database = Wishthis::getDatabase();

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
                if ('string' === gettype($value)) {
                    $this->$key = Sanitiser::render($value);
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
        $database = Wishthis::getDatabase();

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
