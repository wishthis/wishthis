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
    public const WHERE     = '`wishes`.`id` = %d;';

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
                'SELECT ' . self::SELECT    . '
                   FROM ' . self::FROM      . '
              LEFT JOIN ' . self::LEFT_JOIN . '
                  WHERE ' . sprintf(self::WHERE, $id)
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

            $this->title       = Sanitiser::render($this->title);
            $this->description = Sanitiser::render($this->description);
        }
    }

    public function getCard(int $ofUser): string
    {
        ob_start();

        $userCard        = User::getFromID($ofUser);
        $numberFormatter = new \NumberFormatter(
            $userCard->getLocale(),
            \NumberFormatter::CURRENCY
        );

        $userIsCurrent = isset($_SESSION['user']->id) && $_SESSION['user']->id === $userCard->id;

        /**
         * Card
         */
        if ($this->url) {
            $generateCache = $this->cache->generateCache() || !$this->url ? 'true' : 'false';
        } else {
            $generateCache = 'false';
        }
        ?>

        <div class="ui blurring dimmable fluid card stretch"
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
                                        /** TRANSLATORS: %s: Duration (i. e. 30 minutes) */
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

            <div class="image">
                <?php if ($this->priority && isset(self::$priorities[$this->priority])) { ?>
                    <div class="ui small <?= self::$priorities[$this->priority]['color'] ?> right ribbon label">
                        <?= self::$priorities[$this->priority]['name'] ?>
                    </div>
                <?php } ?>

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

                <?php if (isset($this->info->favicon) && $this->info->favicon) { ?>
                    <img class="favicon" src="<?= $this->info->favicon ?>" loading="lazy" />
                <?php } ?>

                <?php if (isset($this->info->providerName) && $this->info->providerName) { ?>
                    <span class="provider"><?= $this->info->providerName ?></span>
                <?php } ?>
            </div>

            <div class="content">
                <?php if ($this->title) { ?>
                    <div class="header">
                        <?php if ($this->url) { ?>
                            <a href="<?= $this->url ?>" target="_blank"><?= $this->title ?></a>
                        <?php } else { ?>
                            <?= $this->title ?>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if ($this->price) { ?>
                    <div class="meta">
                        <span class="date"><?= $numberFormatter->format($this->price) ?></span>
                    </div>
                <?php } ?>

                <div class="description">
                    <?php if ($this->description) { ?>
                        <?= $this->description ?>
                    <?php } elseif ($this->url && !$this->title) { ?>
                        <a href="<?= $this->url ?>" target="_blank"><?= $this->url ?></a>
                    <?php } ?>
                </div>
                <div class="description-fade"></div>
            </div>

            <div class="extra content buttons">
                <?php if (!$userIsCurrent) { ?>
                    <a class="ui small primary labeled icon button fulfil"
                       title="<?= __('Fulfil wish') ?>"
                    >
                        <i class="gift icon"></i>
                        <?= __('Fulfil wish') ?>
                    </a>
                <?php } ?>

                <?php if ($this->url) { ?>
                    <a class="ui small labeled icon button<?= $userIsCurrent ? ' primary' : '' ?>"
                       href="<?= $this->url ?>" target="_blank"
                       title="<?= __('Visit') ?>"
                    >
                        <i class="external icon"></i>
                        <?= __('Visit') ?>
                    </a>
                <?php } ?>

                <?php if ($userIsCurrent) { ?>
                    <div class="ui small labeled icon top left pointing dropdown button options"
                         title="<?= __('Options') ?>"
                    >
                        <i class="cog icon"></i>
                        <span class="text"><?= __('Options') ?></span>
                        <div class="menu">

                            <button class="item wish-fulfilled">
                                <i class="check icon"></i>
                                <?= __('Mark as fulfilled') ?>
                            </button>

                            <button class="item wish-edit" data-id="<?= $this->id ?>">
                                <i class="pen icon"></i>
                                <?= __('Edit') ?>
                            </button>

                            <button class="item wish-delete">
                                <i class="trash icon"></i>
                                <?= __('Delete') ?>
                            </button>

                        </div>
                    </div>
                <?php } ?>

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
}
