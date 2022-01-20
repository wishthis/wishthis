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

    public mixed $data;
    public mixed $products;

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

    public function getCards(): void
    {
        $products = array_filter($this->products, function ($product) {
            if ('unavailable' !== $product['status']) {
                return true;
            }
        });

        if (!empty($products)) { ?>
            <div class="ui three column stackable grid wishlist-cards">

                <?php foreach ($products as $product) {
                    /**
                     * @link https://github.com/oscarotero/Embed
                     */
                    $embed = new \Embed\Embed();
                    $info  = $embed->get($product['url']);
                    ?>
                    <div class="column">
                        <div class="ui fluid card" data-id="<?= $product['id'] ?>">

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
                                        <?= $info->keywords ?>
                                    </div>
                                <?php } ?>

                                <?php if ($info->description) { ?>
                                    <div class="description">
                                        <?= $info->description ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="extra content">
                                <?php if ($info->publishedTime) { ?>
                                    <span class="right floated">
                                        <?= $info->publishedTime ?>
                                    </span>
                                <?php } ?>
                                <?php if ($info->providerName) { ?>
                                    <?= $info->providerName ?>
                                <?php } ?>
                            </div>
                            <div class="extra content">
                                <a class="ui tiny button commit">Commit</a>
                            </div>

                        </div>
                    </div>
                <?php } ?>

            </div>
        <?php } else { ?>
            <?php if (isset($_GET['wishlist'])) { ?>
                <div class="ui icon message">
                    <i class="info circle icon"></i>
                    <div class="content">
                        <div class="header">
                            Empty
                        </div>
                        <p>This wishlist seems to be empty.</p>
                    </div>
                </div><?php
            }
        }
    }
}
