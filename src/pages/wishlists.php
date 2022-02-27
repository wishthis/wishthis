<?php

/**
 * Template for wishlists.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, User, Wishlist};

$page = new page(__FILE__, 'Wishlists');
$page->header();
$page->navigation();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>
        <p>Here you can view and edit all of your wishlists.</p>

        <h2 class="ui header">View</h2>

        <div class="ui horizontal stackable segments">

            <div class="ui segment">
                <p>Please select a wishlist to view.</p>

                <div class="ui form">
                    <div class="field">
                        <label>Wishlist</label>
                        <select class="ui fluid search selection dropdown loading wishlists" name="wishlist">
                            <option value="">Loading your wishlists...</option>
                        </select>
                    </div>
                </div>

                <div class="ui divider"></div>

                <div class="flex">
                    <a class="ui small labeled icon button wishlist-wish-add disabled">
                        <i class="add icon"></i>
                        Add a wish
                    </a>

                    <a class="ui small labeled icon button wishlist-share disabled" target="_blank">
                        <i class="share icon"></i>
                        Share
                    </a>

                    <form class="ui form wishlist-delete" method="post" style="display: inline-block;">
                        <input type="hidden" name="wishlist_delete_id" />

                        <button class="ui fluid small labeled red icon button disabled" type="submit">
                            <i class="trash icon"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="ui segment">
                <p>General options.</p>

                <a class="ui small labeled icon button wishlist-create">
                    <i class="add icon"></i>
                    Create a wishlist
                </a>
            </div>

        </div>

        <h2 class="ui header">Wishes</h2>

        <div class="ui primary progress">
            <div class="bar">
                <div class="progress"></div>
            </div>
        </div>

        <div class="wishlist-cards"></div>
    </div>
</main>

<!-- Modal: Default -->
<div class="ui modal default">
    <div class="header"></div>
    <div class="content"></div>
    <div class="actions"></div>
</div>

<!-- Wishlist: Create -->
<div class="ui modal wishlist-create">
    <div class="header">
        Create a wishlist
    </div>
    <div class="content">
        <div class="description">
            <div class="ui header">Wishlist</div>
            <p>
                Choose a new name for your wishlist.
                Here's a suggestion to get you started.
            </p>

            <form class="ui form">
                <div class="field">
                    <label>Name</label>
                    <input type="text"
                           name="wishlist-name"
                           placeholder="<?= getCurrentSeason() ?>"
                           value="<?= getCurrentSeason() ?>"
                    />
                </div>
            </form>
        </div>
    </div>
    <div class="actions">
        <div class="ui approve primary button create">
            Create
        </div>
        <div class="ui deny button cancel">
            Cancel
        </div>
    </div>
</div>

<!-- Wishlist: Add a wish -->
<div class="ui modal wishlist-wish-add">
    <div class="header">
        Add a wish
    </div>
    <div class="content">
        <div class="description">
            <div class="ui header">Wish</div>
            <p>Fill out any or all of the below fields to add your new wish.</p>

            <form class="ui form wishlist-wish-add" method="post">
                <input type="hidden" name="wishlist_id" />

                <div class="field">
                    <label>Title</label>
                    <input type="text" name="wish_title" maxlength="128" />
                </div>

                <div class="field">
                    <label>Description</label>
                    <textarea name="wish_description"></textarea>
                </div>

                <div class="field">
                    <label>URL</label>

                    <div class="ui input url">
                        <input type="url" name="wish_url" maxlength="255" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="actions">
        <div class="ui primary approve button">
            Add
        </div>
        <div class="ui deny button">
            Cancel
        </div>
    </div>
</div>

<?php
$page->footer();
