<?php

/**
 * Template for viewing a wish.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\{Page, Wish};

$userIsAuthenticated = false;

$wish = new Wish($_GET['id'], false);
$page = new Page(__FILE__, $wish->title);

if ('POST' === $_SERVER['REQUEST_METHOD'] && count($_POST) >= 0) {
    $wish_id          = $_POST['wish_id'];
    $wish_title       = trim($_POST['wish_title']);
    $wish_description = trim($_POST['wish_description']);
    $wish_url         = trim($_POST['wish_url']);
    $wish_priority    = $_POST['wish_priority'] ?: 'NULL';

    $database
    ->query('UPDATE `wishes`
                SET `title`       = "' . $wish_title . '",
                    `description` = "' . $wish_description . '",
                    `url`         = "' . $wish_url . '",
                    `priority`    = ' . $wish_priority . '
              WHERE `id`          = ' . $wish_id . ';');

    $wish             = new Wish($_GET['id'], false);
    $page             = new Page(__FILE__, $wish->title);
    $page->messages[] = Page::success('Wish successfully updated.', 'Success');
}

$wishlists = $user->getWishlists($wish->wishlist);

foreach ($wishlists as $wishlist) {
    if ($wish->wishlist === intval($wishlist['id'])) {
        $userIsAuthenticated = true;
        break;
    }
}

if (!$userIsAuthenticated) {
    http_response_code(404);
    ?>
    <h1>Not found</h1>
    <p>The requested Wish was not found.</p>
    <?php
    die();
}

$page->header();
$page->bodyStart();
$page->navigation();

$referer = '/?page=wishlists&wishlist=' . $wish->wishlist;
?>

<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?= $page->messages() ?>

        <div class="ui grid">
            <div class="row">
                <div class="sixteen wide column">

                    <?php if ($wish->image) { ?>
                        <img class="ui fluid rounded image preview" src="<?= $wish->image ?>" />
                    <?php } ?>

                </div>
            </div>

            <div class="row">
                <div class="sixteen wide column">

                    <a class="ui labeled icon button" href="<?= $wish->url ?>" target="_blank">
                        <i class="external icon"></i>
                        Visit
                    </a>

                    <button class="ui labeled icon button auto-fill disabled"
                            type="button"
                    >
                        <i class="redo icon"></i>
                        Auto-fill
                    </button>

                </div>
            </div>
        </div>

        <div class="ui segment">
            <form class="ui form wish" method="POST">
                <input type="hidden" name="wish_id" value="<?= $_GET['id'] ?>">

                <div class="ui two column grid">

                    <div class="stackable row">
                        <div class="column">

                            <div class="field">
                                <label>Title</label>

                                <div class="ui input">
                                    <input type="text"
                                           name="wish_title"
                                           placeholder="<?= $wish->title ?>"
                                           value="<?= $wish->title ?>"
                                           maxlength="128"
                                    />
                                </div>
                            </div>

                            <div class="field">
                                <label>Description</label>

                                <textarea name="wish_description"
                                          placeholder="<?= $wish->description ?>"
                                ><?= $wish->description ?></textarea>
                            </div>

                        </div>

                        <div class="column">

                            <div class="field">
                                <label>URL</label>

                                <input type="url"
                                       name="wish_url"
                                       placeholder="<?= $wish->url ?>"
                                       value="<?= $wish->url ?>"
                                       maxlength="255"
                                />
                            </div>

                            <div class="field">
                                <label>Priority</label>

                                <select class="ui selection clearable dropdown priority"
                                        name="wish_priority"
                                >
                                    <?php foreach (Wish::$priorities as $priority => $item) { ?>
                                        <?php if ($wish->priority === $priority) { ?>
                                            <option value="<?= $priority ?>" selected><?= $item['name'] ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $priority ?>"><?= $item['name'] ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="stackable row">
                        <div class="sixteen wide column">

                            <input class="ui primary button" type="submit" value="Save" />
                            <input class="ui button" type="reset" value="Reset" />
                            <a class="ui secondary button" href="<?= $referer ?>">Back</a>

                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
</main>

<!-- Preview -->
<div class="ui small modal preview">
    <div class="header">
        Image
    </div>
    <div class="content">
        <p>Define a new URL to be used as a preview.</p>

        <form class="ui form preview">
            <input type="hidden" name="wish_id" value="<?= $_GET['id'] ?>" />

            <div class="field">
                <label>URL</label>
                <input class="current" type="url" name="wish_url" />
            </div>
        </form>
    </div>
    <div class="actions">
        <div class="ui primary approve button">
            Save
        </div>
        <div class="ui deny button">
            Discard
        </div>
    </div>
</div>

<!-- Auto-fill -->
<div class="ui small modal auto-fill">
    <div class="header">
        Warning
    </div>
    <div class="content">
        <p>This action will potentially overwrite all fields in this wish.</p>
        <p>Would you like to continue?</p>
    </div>
    <div class="actions">
        <div class="ui primary approve button">
            Yes, overwrite
        </div>
        <div class="ui deny button">
            No
        </div>
    </div>
</div>

<!-- Validate -->
<div class="ui small modal validate">
    <div class="header">
        URL mismatch
    </div>
    <div class="content">
        <div class="description">
            <p>
                The URL you have entered does not seem quite right. Would you like to update it with the one I found?
            </p>
            <p class="provider">
                According to <strong class="providerName">Unknown</strong>, this is the canonical (correct) URL.
            </p>

            <div class="ui form urls">
                <div class="field">
                    <label>Current</label>
                    <input class="current" type="url" readonly />
                </div>

                <div class="field">
                    <label>Proposed</label>
                    <input class="proposed" type="url" />
                </div>
            </div>
        </div>
    </div>
    <div class="actions">
        <div class="ui primary approve button">
            Yes, update
        </div>
        <div class="ui deny button">
            No, leave it
        </div>
    </div>
</div>

<?php
$page->footer();
$page->bodyEnd();
?>
