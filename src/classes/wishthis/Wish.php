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

    public static function getFromId(int $wishId): self|false
    {
        global $database;

        $wishQuery = $database->query(
            'SELECT *
               FROM `wishes`
              WHERE `wishes`.`id` = :wish_id',
            array(
                'wish_id' => $wishId,
            )
        );

        if (false === $wishQuery) {
            return false;
        }

        $wishData = $wishQuery->fetch();
        $wish     = new self($wishData);

        return $wish;
    }

    public static function getAffiliateLink(string $url): string
    {
        $urlParts = parse_url($url);

        if (isset($urlParts['query'])) {
            \parse_str($urlParts['query'], $urlParameters);
        } else {
            $urlParameters = array();
        }

        if (\str_contains($urlParts['host'], 'amazon')) {
            $urlParameters['tag'] = 'grandel-21';
            $urlParts['query']    = \http_build_query($urlParameters);
            $url                  = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . '?' . $urlParts['query'];
        }

        return $url;
    }

    /**
     * Non-Static
     */
    private ?Cache\Embed $cache = null;

    /**
     * The unique wish id.
     *
     * @var int
     */
    private int $id;

    /**
     * The wishlist id for this wish.
     *
     * TODO: rename this to wishlist_id (the database column too).
     *
     * @var int
     */
    private int $wishlist;

    /**
     * The wish title.
     *
     * @var string|null
     */
    private string $title;

    /**
     * The wish description.
     *
     * @var string|null
     */
    private string $description;

    /**
     * The wish image url.
     *
     * @var string|null
     */
    private string $image;

    /**
     * The wish (product) url.
     *
     * @var string|null
     */
    private string $url;

    /**
     * The wish priority
     *
     * @var int|null
     */
    private ?int $priority;

    /**
     * The wish status.
     *
     * @var string|null
     */
    private ?string $status;

    /**
     * Whether this wish is purchasable.
     *
     * @var bool
     */
    private bool $is_purchasable;

    /**
     * A unix timestamp of when this wish was last edited.
     *
     * @var int|null
     */
    private ?int $edited;

    public string $style = 'grid';

    /** Product */
    public ?float $price = null;

    /** Other */
    public \stdClass $info;

    public bool $exists = false;

    public function __construct(array $wishData)
    {
        $this->id             = $wishData['id'];
        $this->wishlist       = $wishData['wishlist'];
        $this->title          = stripslashes($wishData['title']       ?? '');
        $this->description    = stripslashes($wishData['description'] ?? '');
        $this->image          = $wishData['image'] ?? '';
        $this->url            = $wishData['url'] ?? '';
        $this->priority       = $wishData['priority'];
        $this->status         = $wishData['status'];
        $this->is_purchasable = $wishData['is_purchasable'];
        $this->edited         = $wishData['edited'] ? \strtotime($wishData['edited']) : null;
    }

    public function getCard(int $ofUser): string
    {
        ob_start();

        $userCard        = User::getFromID($ofUser);
        $userCurrent     = User::getCurrent();
        $numberFormatter = new \NumberFormatter(
            $userCard->getLocale() . '@currency=' . $userCard->getCurrency(),
            \NumberFormatter::CURRENCY
        );
        $userIsCurrent   = $userCurrent->isLoggedIn() && $userCurrent->getId() === $userCard->getId();

        /**
         * Card
         */
        if ($this->url && $this->cache) {
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
                <?php if ($this->title || $this->priority) { ?>
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

    public function serialise(): array
    {
        $wishArray = array(
            'id'             => $this->id,
            'wishlist'       => $this->wishlist,
            'title'          => $this->title,
            'description'    => $this->description,
            'image'          => $this->image,
            'url'            => $this->url,
            'priority'       => $this->priority,
            'status'         => $this->status,
            'is_purchasable' => $this->is_purchasable,
            'edited'         => $this->edited,
        );

        return $wishArray;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getWishlistId(): int
    {
        return $this->wishlist;
    }
}
