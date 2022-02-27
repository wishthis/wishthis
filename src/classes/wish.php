<?php

/**
 * A wish
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class Wish
{
    public int $id;
    public int $wishlist;
    public ?string $title;
    public ?string $description;
    public ?string $url;
    public ?string $status;

    public function __construct(int|array $wish)
    {
        global $database;

        $columns = array();

        if (is_int($wish)) {
            $wish = $database
            ->query('SELECT *
                       FROM `wishes`
                      WHERE `id` = ' . $wish . ';')
            ->fetch();

            $columns = $wish;
        } elseif (is_array($wish)) {
            $columns = $wish;
        }

        if ($columns) {
            foreach ($columns as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function getCard(int $ofUser, bool $generateCache = false): string
    {
        ob_start();

        /**
         * Card
         */
        $userIsCurrent = isset($_SESSION['user']['id']) && intval($_SESSION['user']['id']) === $ofUser;

        if ($this->url) {
            $cache  = new EmbedCache($this->url);
            $info   = $cache->get($generateCache);
            $exists = $cache->exists() || !$this->url ? 'true' : 'false';
        } else {
            $exists = 'true';
        }

        $title       = $this->title       ?? $info->title       ?? null;
        $description = $this->description ?? $info->description ?? null;
        $url         = $this->url         ?? $info->url         ?? null;

        $image        = $info->image        ? $info->image : '/src/assets/img/no-image.svg';
        $favicon      = $info->favicon      ?? null;
        $providerName = $info->providerName ?? null;
        $keywords     = $info->keywords     ?? null;
        ?>

        <div class="ui fluid card stretch" data-id="<?= $this->id ?>" data-cache="<?= $exists ?>">
            <div class="overlay"></div>

            <div class="image">
                <?php if ($image) { ?>
                    <img class="preview" src="<?= $image ?>" loading="lazy" />
                <?php } ?>

                <?php if ($favicon) { ?>
                    <img class="favicon" src="<?= $favicon ?>" loading="lazy" />
                <?php } ?>

                <?php if ($providerName) { ?>
                    <span class="provider"><?= $providerName ?></span>
                <?php } ?>

                <?php if ($userIsCurrent && $url) { ?>
                    <button class="ui icon button refresh">
                        <i class="refresh icon"></i>
                    </button>
                <?php } ?>
            </div>

            <div class="content">
                <?php if ($title) { ?>
                    <div class="header">
                        <?php if ($url) { ?>
                            <a href="<?= $url ?>" target="_blank"><?= $title ?></a>
                        <?php } else { ?>
                            <?= $title ?>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if ($keywords) { ?>
                    <div class="meta">
                        <?= implode(', ', $keywords) ?>
                    </div>
                <?php } ?>

                <?php if ($description) { ?>
                    <div class="description">
                        <?= $description ?>
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

                <?php if ($url) { ?>
                    <a class="ui small labeled icon button" href="<?= $url ?>" target="_blank">
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
        <?php

        $html = ob_get_clean();

        return $html;
    }
}
