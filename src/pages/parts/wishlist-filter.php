<?php

/**
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$scriptPart = '/src/assets/js/parts/wishlist-filter.js';
?>
<script defer src="<?= $scriptPart ?>?m=<?= filemtime(ROOT . $scriptPart) ?>"></script>

<div>
    <div class="ui stackable grid">
        <div class="column">

            <div class="ui floating dropdown labeled icon button filter priority">
                <input type="hidden" name="filters" />

                <i class="filter icon"></i>
                <span class="text"><?= __('Filter priorities') ?></span>

                <div class="menu">
                    <div class="ui icon search input">
                        <i class="search icon"></i>
                        <input type="text" placeholder="<?= __('Search priorities') ?>" />
                    </div>

                    <div class="divider"></div>

                    <div class="header">
                        <i class="filter icon"></i>
                        <?= __('Priorities') ?>
                    </div>

                    <div class="scrolling menu">
                        <div class="item" data-value="-1">
                            <i class="ui empty circular label"></i>
                            <?= __('All priorities') ?>
                        </div>

                        <div class="item" data-value="">
                            <i class="ui white empty circular label"></i>
                            <?= __('No priority') ?>
                        </div>

                        <div class="divider"></div>

                        <?php foreach (Wish::$priorities as $number => $priority) { ?>
                            <div class="item" data-value="<?= $number ?>">
                                <i class="ui <?= $priority['color'] ?> empty circular label"></i>
                                <?= $priority['name'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
