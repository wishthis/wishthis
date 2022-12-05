<?php

/**
 * Changelog
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Changelog'));
$page->header();
$page->bodyStart();
$page->navigation();
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <div class="ui stackable grid">

            <div class="four wide column">
                <div class="ui vertical pointing fluid menu profile">
                    <a class="item" data-tab="0-7-3"><?= __('0.7.3') ?></a>
                    <a class="item" data-tab="0-7-2"><?= __('0.7.2') ?></a>
                    <a class="item" data-tab="0-7-1"><?= __('0.7.1') ?></a>
                    <a class="item" data-tab="0-7-0"><?= __('0.7.0') ?></a>
                    <a class="item" data-tab="0-6-0"><?= __('0.6.0') ?></a>
                </div>
            </div>

            <div class="ui tab" data-tab="0-7-3">
                <div class="ui segments">
                    <div class="ui segment">
                        <h2 class="ui header"><?= __('0.7.3') ?></h2>
                    </div>
                    <div class="ui segment">
                        <h3 class="ui header"><?= __('Fixed') ?></h3>
                        <ul>
                            <li>
                                <?php
                                    /** TRANSLATORS: Changelog: Fixed */
                                    echo  __('Fix fulfilled wishes disappearing for wishlist owner');
                                ?>
                                <a href="https://github.com/grandeljay/wishthis/issues/58" target="_blank">#58</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="twelve wide stretched column">
                <div class="ui tab" data-tab="0-7-2">
                    <div class="ui segments">

                        <div class="ui segment">
                            <h2 class="ui header"><?= __('0.7.2') ?></h2>
                        </div>

                        <div class="ui segment">
                            <h3 class="ui header"><?= __('Fixed') ?></h3>
                            <ul>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo  __('Fix fulfilled wishes showing atfer using filter');
                                    ?>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <div class="ui tab" data-tab="0-7-1">
                    <div class="ui segments">

                        <div class="ui segment">
                            <h2 class="ui header"><?= __('0.7.1') ?></h2>
                        </div>

                        <div class="ui segment">
                            <h3 class="ui header"><?= __('Improved') ?></h3>
                            <ul>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Improved */
                                        echo __('MJML settings page');
                                    ?>
                                    <a href="https://github.com/grandeljay/wishthis/issues/47" target="_blank">#47</a>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Improved */
                                        echo __('Expired sessions are now invalidated by wishthis instead of relying on the browser to delete the cookies.');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Improved */
                                        echo __('Dark theme');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Improved */
                                        echo __('Remembered lists design');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Improved */
                                        echo __('Translations');
                                    ?>
                                </li>
                            </ul>

                            <h3 class="ui header"><?= __('Fixed') ?></h3>
                            <ul>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo  __('Label on top of dropdown menu');
                                    ?>
                                    <a href="https://github.com/grandeljay/wishthis/issues/44" target="_blank">#44</a>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo  __('Encoding issue in wish description');
                                    ?>
                                    <a href="https://github.com/grandeljay/wishthis/issues/45" target="_blank">#45</a>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo  __('Caching issue with the blog');
                                    ?>
                                    <a href="https://github.com/grandeljay/wishthis/issues/46" target="_blank">#46</a>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo  __('An error when a blog post doesn\'t have a featured image.');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo  __('Version number not being stored correctly causing the migration to execute the wrong script.');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo  __('Wishlist filter cut off on mobile');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo  __('Wish options not selectable after filtering');
                                    ?>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <div class="ui tab" data-tab="0-7-0">
                    <div class="ui segments">

                        <div class="ui segment">
                            <h2 class="ui header"><?= __('0.7.0') ?></h2>
                        </div>

                        <div class="ui segment">
                            <h3 class="ui header"><?= __('Added') ?></h3>
                            <ul>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Added */
                                        echo __('Blog')
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Added */
                                        echo __('Dark theme')
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Added */
                                        echo __('Wish properties. You can now mark a wish as purchasable and add a price.')
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Added */
                                        echo __('Jump to last edited wishlist from home')
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Added */
                                        echo __('Quick add wish from home')
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Added */
                                        echo __('Button to request more wishes from a users wishlist')
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Added */
                                        echo __('Option to stay logged in')
                                    ?>
                                </li>
                            </ul>

                            <h3 class="ui header"><?= __('Improved') ?></h3>
                            <ul>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Improved */
                                        echo __('Localisation (many new translations added)');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Improved */
                                        echo __('Additional logins are no longer required when switching between wishthis channels');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Improved */
                                        echo __('Remembered wishlists design');
                                    ?>
                                </li>
                            </ul>

                            <h3 class="ui header"><?= __('Changed') ?></h3>
                            <ul>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Changed */
                                        echo __('Changelog is now a page instead of a downloadable markdown file');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Changed */
                                        echo __('Wishes can be edited from the wishlist now, without loading another page');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Changed */
                                        echo __('"Saved wishlists" has been renamed to "Remember lists"');
                                    ?>
                                </li>
                            </ul>

                            <h3 class="ui header"><?= __('Fixed') ?></h3>
                            <ul>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo __('Various minor things (typos, menu order, etc)');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo __('Wish information being updated with 404 content from URL');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo __('Wish image not showing');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo __('An error when saving a wish with a really long URL');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo __('Redirect errors on Nginx');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Fixed */
                                        echo __('An error when fetching title from an URL containing quotes');
                                    ?>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <div class="ui tab" data-tab="0-6-0">
                    <div class="ui segments">

                        <div class="ui segment">
                            <h2 class="ui header"><?= __('0.6.0') ?></h2>
                        </div>

                        <div class="ui segment">
                            <h3 class="ui header"><?= __('Added') ?></h3>
                            <ul>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Added */
                                        echo __('This changelog');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Added */
                                        echo __('Wish properties');
                                    ?>
                                </li>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Added */
                                        echo __('Button to mark wish as fulfilled');
                                    ?>
                                </li>
                            </ul>

                            <h3 class="ui header"><?= __('Improved') ?></h3>
                            <ul>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Improved */
                                        echo __('Card design');
                                    ?>
                                </li>
                            </ul>

                            <h3 class="ui header"><?= __('Fixed') ?></h3>
                            <ul>
                                <li>
                                    <?php
                                        /** TRANSLATORS: Changelog: Improved */
                                        echo __('Various small bugs');
                                    ?>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</main>

<?php
$page->bodyEnd();
?>
