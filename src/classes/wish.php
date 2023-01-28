<?php

/**
 * A wish
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class Wish
{
    /**
     * Static
     */
    public const SELECT    = '`wishes`.*, `products`.`price`';
    public const FROM      = '`wishes`';
    public const LEFT_JOIN = '`products` ON `wishes`.`id` = `products`.`wish`';
    public const WHERE     = '`wishes`.`id` = :wish_id;';

    public const NO_IMAGE = '/src/assets/img/no-image.svg';

    public const STATUS_TEMPORARY         = 'temporary';
    public const STATUS_TEMPORARY_MINUTES = 30;
    public const STATUS_UNAVAILABLE       = 'unavailable';
    public const STATUS_FULFILLED         = 'fulfilled';

    public static array $priorities;

    public static function initialize()
    {
        self::$priorities = array(
            '1' => array(
                'name'  => __('Unsure about it'),
                'color' => 'teal',
            ),
            '2' => array(
                'name'  => __('Nice to have'),
                'color' => 'olive',
            ),
            '3' => array(
                'name'  => __('Would love it'),
                'color' => 'yellow',
            ),
        );
    }

    /**
     * Non-Static
     */
    private Cache\Embed $cache;

    /** General */
    public int $id;
    public int $wishlist;
    public ?string $title;
    public ?string $description;
    public ?string $image;
    public ?string $url;
    public ?int $priority;
    public bool $is_purchasable;
    public ?string $status;
    public string $style = 'grid';

    /** Product */
    public ?float $price;

    /** Other */
    public \stdClass $info;

    public bool $exists = false;

    public function __construct(int|array $idOrColumns, bool $generateCache = false)
    {
        global $database;

        $columns = array();

        if (is_numeric($idOrColumns)) {
            $id      = $idOrColumns;
            $columns = $database
            ->query(
                '  SELECT ' . self::SELECT    . '
                     FROM ' . self::FROM      . '
                LEFT JOIN ' . self::LEFT_JOIN . '
                    WHERE ' . self::WHERE,
                array(
                    'wish_id' => $id,
                )
            )
            ->fetch();
        } elseif (is_array($idOrColumns)) {
            $columns = $idOrColumns;
        }

        if ($columns) {
            $this->exists = true;

            foreach ($columns as $key => $value) {
                $this->$key = $value;
            }

            $this->info = new \stdClass();

            if ($this->url) {
                $this->cache = new Cache\Embed($this->url);
                $this->info  = $this->cache->get($generateCache);
            }

            foreach ($columns as $key => $value) {
                if (empty($value) && isset($this->info->$key)) {
                    $this->$key = $this->info->$key;
                }
            }

            $this->title       = Sanitiser::render($this->title ?? '');
            $this->description = Sanitiser::render($this->description ?? '');
        }
    }

    public function getCard(int $ofUser): string
    {
        ob_start();

        $userCard        = User::getFromID($ofUser);
        $numberFormatter = new \NumberFormatter(
            $userCard->getLocale() . '@currency=' . $userCard->getCurrency(),
            \NumberFormatter::CURRENCY
        );
        $userIsCurrent   = isset($_SESSION['user']->id) && $_SESSION['user']->id === $userCard->id;

        /**
         * Card
         */
        if ($this->url) {
            $generateCache = $this->cache->generateCache() ? 'true' : 'false';
        } else {
            $generateCache = 'false';
        }

        $card_style = 'list' === $this->style ? 'horizontal card' : 'card';
        ?>
        <div class="ui blurring dimmable fluid <?= $card_style ?> wish"
            data-id="<?= $this->id ?>"
            data-cache="<?= $generateCache ?>"
        >
            <div class="ui inverted dimmer">
                <div class="content">
                    <div class="center">
                        <div class="ui icon header">
                        <i class="history icon"></i>
                            <div class="content">
                                <?= __('Wish temporarily fulfilled') ?>
                                <div class="sub header">
                                    <?php
                                    printf(
                                        /** TRANSLATORS: %s: Duration (e. g. 30 minutes) */
                                        __('If this wish is a product, confirm the order was successful and mark it as fulfilled here. If you do not confirm this wish as fulfilled, it will become available again to others after %s.'),
                                        sprintf(
                                            /** TRANSLATORS: %d Amount of minutes */
                                            '<strong>' . __('%d minutes') . '</strong>',
                                            self::STATUS_TEMPORARY_MINUTES
                                        )
                                    )
                                    ?>
                                </div>
                            </div>
                        </div>

                        <button class="ui positive labeled icon button confirm">
                            <i class="check double icon"></i>
                            <?= __('Confirm') ?>
                        </button>
                    </div>
                </div>
            </div>

            <?= $this->getCardImage() ?>

            <div class="content">
                <?php if ($this->title) { ?>
                    <?= $this->getCardContentHeader() ?>
                <?php } ?>

                <?= $this->getCardContentDescription() ?>

                <?php if (!empty($this->info->favicon) || !empty($this->info->providerName) || $this->price) { ?>
                    <div class="meta">
                        <?php if (!empty($this->info->favicon) || !empty($this->info->providerName)) { ?>
                            <div class="provider">
                                <?php if (!empty($this->info->favicon)) { ?>
                                    <img class="favicon" src="<?= $this->info->favicon ?>" loading="lazy" />
                                <?php } ?>

                                <?php if (!empty($this->info->providerName)) { ?>
                                    <span class="provider"><?= $this->info->providerName ?></span>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if ($this->price) { ?>
                            <div class="price">
                                <?= $numberFormatter->format($this->price) ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?= $this->getCardButtons($userIsCurrent) ?>
            </div>
        </div>
        <?php

        $html = ob_get_clean();

        return $html;
    }

    public function getTitle(): string
    {
        $title = __('Wish not found');

        if ($this->exists) {
            $title = $this->title
                  ?: $this->description
                  ?: $this->url
                  ?: $this->id;
        }

        return $title;
    }

    private function getCardImage(): string
    {
        ob_start();
        ?>
        <div class="image">
            <?php if ($this->image) { ?>
                <?php if ('svg' === pathinfo($this->image, PATHINFO_EXTENSION)) { ?>
                    <?php if (file_exists(ROOT . $this->image)) { ?>
                        <?= file_get_contents(ROOT . $this->image) ?>
                    <?php } else { ?>
                        <?= file_get_contents($this->image) ?>
                    <?php } ?>
                <?php } else { ?>
                    <img class="preview" src="<?= $this->image ?>" loading="lazy" />
                <?php } ?>
            <?php } else { ?>
                <?= file_get_contents(ROOT . self::NO_IMAGE) ?>
            <?php } ?>
        </div>
        <?php
        $image = ob_get_clean();

        return $image;
    }

    private function getCardContentHeader(): string
    {
        ob_start();
        ?>
        <div class="header">
            <?= $this->getCardPriority() ?>

            <?php if ($this->url) { ?>
                <a href="<?= $this->url ?>" target="_blank"><?= $this->title ?></a>
            <?php } else { ?>
                <?= $this->title ?>
            <?php } ?>
        </div>
        <?php
        $content_header = ob_get_clean();

        return $content_header;
    }

    private function getCardContentMeta(string $price): string
    {
        ob_start();
        ?>
        <div class="meta">
            <span class="date"><?= $price ?></span>
        </div>
        <?php
        $content_meta = ob_get_clean();

        return $content_meta;
    }

    private function getCardContentDescription(): string
    {
        ob_start();
        ?>
        <?php if ($this->description) { ?>
            <div class="description">
                <p><?= $this->description ?></p>
            </div>
        <?php } elseif ($this->url && !$this->title) { ?>
            <div class="description">
                <p><a href="<?= $this->url ?>" target="_blank"><?= $this->url ?></a></p>
            </div>
        <?php } ?>
        <?php
        $content_description = ob_get_clean();

        return $content_description;
    }

    private function getCardPriority(): string
    {
        ob_start();
        ?>
        <?php if ($this->priority && isset(self::$priorities[$this->priority])) { ?>
            <div class="ui small <?= self::$priorities[$this->priority]['color'] ?> right label">
                <i class="heart icon"></i>
                <span><?= self::$priorities[$this->priority]['name'] ?></span>
            </div>
        <?php } ?>
        <?php
        $priority = ob_get_clean();

        return $priority;
    }

    private function getCardButtons(bool $userIsCurrent): string
    {
        ob_start();
        ?>
        <div class="extra content buttons">
            <button class="ui compact labeled icon button wish-details">
                <i class="stream icon"></i>
                <span><?= __('Details') ?></span>
            </button>
        </div>
        <?php
        $buttons = ob_get_clean();

        return $buttons;
    }
}
