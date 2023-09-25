<?php

/**
 * Allows administrators to login as a user. For debugging purposes.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

$page = new Page(__FILE__, __('Login as'), 100);
$user = User::getCurrent();

if (isset($_POST['email'])) {
    $email = Sanitiser::getEmail($_POST['email']);

    $userQuery = $database
    ->query(
        'SELECT *
           FROM `users`
          WHERE `email` = :user_email;',
        array(
            'user_email' => $email,
        )
    );

    $success = false !== $userQuery;

    if ($success) {
        $fields = $userQuery->fetch();

        $user = new User($fields);
        $user->logIn();
    }
}

$page->header();
$page->bodyStart();
$page->navigation();

/**
 * Recent users
 */
$users = $database
->query(
    '  SELECT *
         FROM `users`
     ORDER BY `last_login` DESC
        LIMIT 100;'
)
->fetchAll();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?php
        if (isset($success)) {
            if ($success) {
                echo Page::success(sprintf(__('Successfully logged in as %s.'), $user->email), __('Success'));
            } else {
                echo Page::error(__('User not found!'), __('Error'));
            }
        }
        ?>

        <div class="ui segment">
            <form class="ui form" method="POST">
                <div class="field">
                    <label><?= __('Email') ?></label>

                    <select class="ui fluid search selection dropdown" name="email">
                        <?php foreach ($users as $user) { ?>
                            <option value="<?= $user['email'] ?>"><?= $user['email'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <input class="ui primary button"
                       type="submit"
                       value="<?= __('Login') ?>"
                       title="<?= __('Login') ?>"
                />
            </form>
        </div>
    </div>
</main>

<?php
$page->bodyEnd();
