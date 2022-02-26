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
        $this->wishes = $database->query('SELECT *
                                              FROM `wishes`
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
            $wishes = array_filter($this->wishes, function ($wish) use ($exclude) {
                return !in_array($wish['status'], $exclude);
            });
        } else {
            $wishes = $this->wishes;
        }

        /**
         * Cards
         */
        $userIsCurrent = isset($_SESSION['user']) && $this->data['user'] === $_SESSION['user']['id'];
        $cardIndex     = 0;

        if (!empty($wishes)) { ?>
            <div class="ui three column doubling stackable grid">
                <?php foreach ($wishes as $wish) {
                    $cache  = new EmbedCache($wish['url']);
                    $info   = $cache->get(false);
                    $exists = $cache->exists() ? 'true' : 'false';

                    $title = $wish['title'] ?? $info->title;
                    ?>
                    <div class="column">
                        <div class="ui fluid card stretch" data-id="<?= $wish['id'] ?>" data-index="<?= $cardIndex ?>" data-cache="<?= $exists ?>">
                            <div class="overlay"></div>

                            <div class="image">
                                <?php if ($info->image) { ?>
                                    <img class="preview" src="<?= $info->image ?>" loading="lazy"/>
                                <?php } ?>

                                <?php if ($info->favicon) { ?>
                                    <img class="favicon" src="<?= $info->favicon ?>" loading="lazy"/>
                                <?php } ?>

                                <?php if ($info->providerName) { ?>
                                    <span class="provider"><?= $info->providerName ?></span>
                                <?php } ?>

                                <?php if ($userIsCurrent) { ?>
                                    <button class="ui icon button refresh">
                                        <i class="refresh icon"></i>
                                    </button>
                                <?php } ?>
                            </div>

                            <div class="content">
                                <?php if ($title) { ?>
                                    <div class="header">
                                        <?php if ($info->url) { ?>
                                            <a href="<?= $info->url ?>" target="_blank"><?= $title ?></a>
                                        <?php } else { ?>
                                            <?= $title ?>
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
                                    <div class="description-fade"></div>
                                <?php } ?>
                            </div>

                            <div class="extra content buttons">
                                <?php if (!$userIsCurrent) { ?>
                                    <a class="ui small primary labeled icon button commit">
                                        <i class="shopping cart icon"></i>
                                        Commit
                                    </a>
                                <?php } ?>

                                <?php if ($info->url) { ?>
                                    <a class="ui small labeled icon button" href="<?= $info->url ?>" target="_blank">
                                        <i class="external icon"></i>
                                        Visit
                                    </a>
                                <?php } ?>

                                <?php if ($userIsCurrent) { ?>
                                    <a class="ui small labeled red icon button delete">
                                        <i class="trash icon"></i>
                                        Delete
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <? $cardIndex++ ?>
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
