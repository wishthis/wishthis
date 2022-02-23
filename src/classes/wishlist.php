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
    public array $products = array();

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
        $this->data = $database->query('SELECT *
                                          FROM `wishlists`
                                         WHERE `' . $column . '` = ' . $id_or_hash . ';')
                               ->fetch();

        /** Exists */
        if (isset($this->data['id'])) {
            $this->id     = $this->data['id'];
            $this->exists = true;
        } else {
            return;
        }

        /**
         * Get Products
         */
        $this->products = $database->query('SELECT *
                                              FROM `products`
                                             WHERE `wishlist` = ' . $this->id . ';')
                                   ->fetchAll();
    }

    public function getCards($options = array()): string
    {
        ob_start();

        /**
         * Options
         */
        $exclude = isset($options['exclude']) ? $options['exclude'] : array();

        if ($exclude) {
            $products = array_filter($this->products, function ($product) use ($exclude) {
                return !in_array($product['status'], $exclude);
            });
        } else {
            $products = $this->products;
        }

        /**
         * Cards
         */
        if (!empty($products)) { ?>
            <div class="ui stackable three column grid container">
                <?php foreach ($products as $product) {
                    $cache  = new EmbedCache($product['url']);
                    $info   = $cache->get(false);
                    $exists = $cache->exists() ? 'true' : 'false';
                    ?>
                    <div class="column">
                        <div class="ui fluid card stretch" data-id="<?= $product['id'] ?>" data-cache="<?= $exists ?>">

                            <?php if ($info->image) { ?>
                                <div class="image">
                                    <img src="<?= $info->image ?>" />
                                </div>
                            <?php } ?>

                            <div class="content">
                                <?php if ($info->title) { ?>
                                    <div class="header">
                                        <?php if ($info->favicon) { ?>
                                            <img src="<?= $info->favicon ?>" />
                                        <?php } ?>

                                        <?php if ($info->url) { ?>
                                            <a href="<?= $info->url ?>" target="_blank"><?= $info->title ?></a>
                                        <?php } else { ?>
                                            <?= $info->title ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                                <?php if ($info->keywords) { ?>
                                    <div class="meta">
                                        <?= implode(', ', $info->keywords) ?>
                                    </div>
                                <?php } ?>

                                <?php if ($info->description) { ?>
                                    <div class="description">
                                        <?= $info->description ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <?php if ($info->publishedTime || $info->providerName) { ?>
                                <div class="extra content details">
                                    <?php if ($info->publishedTime) { ?>
                                        <span class="right floated">
                                            <?= $info->publishedTime ?>
                                        </span>
                                    <?php } ?>

                                    <?php if ($info->providerName) { ?>
                                        <?= $info->providerName ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <div class="extra content buttons">
                                <?php if ($info->url) { ?>
                                    <a class="ui tiny button" href="<?= $info->url ?>" target="_blank">View</a>
                                <?php } ?>

                                <?php if (isset($_SESSION['user']) && $this->data['user'] === $_SESSION['user']['id']) { ?>
                                    <a class="ui tiny red button delete">Delete</a>
                                <?php } else { ?>
                                    <a class="ui tiny button commit">Commit</a>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="sixteen wide column">
                <?= Page::info('This wishlist seems to be empty.', 'Empty'); ?>
            </div>
            <?php
        }

        $html = ob_get_clean();

        return $html;
    }
}
