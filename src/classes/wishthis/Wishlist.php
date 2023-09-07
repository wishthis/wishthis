<?php

/**
 * A wishlist.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class Wishlist
{
    public static function getFromId(int $id): self|false
    {
        global $database;

        $wishlistQuery = $database->query(
            'SELECT *
               FROM `wishlists`
              WHERE `wishlists`.`id` = :wishlist_id',
            array(
                'wishlist_id' => $id,
            )
        );

        if (false === $wishlistQuery) {
            return false;
        }

        $wishlistData = $wishlistQuery->fetch();
        $wishlist     = new Wishlist($wishlistData);

        return $wishlist;
    }

    public static function getFromHash(string $hash): self|false
    {
        global $database;

        $wishlistQuery = $database->query(
            'SELECT *
               FROM `wishlists`
              WHERE `wishlists`.`hash` = :wishlist_hash',
            array(
                'wishlist_hash' => $hash,
            )
        );

        if (false === $wishlistQuery) {
            return false;
        }

        $wishlistData = $wishlistQuery->fetch();
        $wishlist     = new Wishlist($wishlistData);

        return $wishlist;
    }

    /**
     * The unique wishlist id.
     *
     * @var int
     */
    private int $id;

    /**
     * The user id this wishlist belongs to.
     *
     * TODO: rename this to user_id (the database column too).
     *
     * @var int
     */
    private int $user;

    /**
     * The wishlist name.
     *
     * @var string
     */
    private string $name;

    /**
     * The unique wishlist hash.
     *
     * @var string
     */
    private string $hash;

    /**
     * A unix timestamp of when the last notification was sent to the wishlist
     * owner.
     *
     * @var int
     */
    private int $notification_sent;

    public array $wishes = array();

    public bool $exists = false;

    public function __construct(array $wishlist_data)
    {
        $this->id                = $wishlist_data['id'];
        $this->user              = $wishlist_data['user'];
        $this->name              = $wishlist_data['name'];
        $this->hash              = $wishlist_data['hash'];
        $this->notification_sent = $wishlist_data['notification_sent'] ? \strtotime($wishlist_data['notification_sent']) : 0;
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

        $user = User::getCurrent();

        if ($user->isLoggedIn()) {
            $wishlist_ids = array_map(
                function ($wishlist_data) {
                    return intval($wishlist_data['id']);
                },
                $user->getWishlists()
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

        if ($this->name) {
            $title = $this->name;
        }

        return $title;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getNotificationSent(): int
    {
        return $this->notification_sent;
    }
}
