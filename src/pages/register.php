<?php

/**
 * Register a new user
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page     = new page(__FILE__, 'Register');
$messages = array();

if (isset($_POST['email'], $_POST['password']) && !empty($_POST['planet'])) {
    $users  = $database->query('SELECT * FROM `users`;')->fetchAll();
    $emails = array_map(
        function($user) {
            return $user['email'];
        },
        $users
    );

    $isHuman    = false;
    $planet     = strtolower($_POST['planet']);
    $planetName = strtoupper($planet[0]) . substr($planet, 1);
    $planets    = array(
        'mercury',
        'venus',
        'earth',
        'mars',
        'jupiter',
        'saturn',
        'uranus',
        'neptune',
    );
    $not_planets = array(
        'pluto',
        'sun'
    );

    if (in_array($planet, array_merge($planets, $not_planets))) {
        $isHuman = true;
    }

    if (in_array($planet, $not_planets)) {
        $messages[] = Page::warning('<strong>' . $planetName . '</strong> is not a planet but I\'ll let it slide, since only a human would make this kind of mistake.', 'Invalid planet');
    }

    if ($isHuman) {
        if (0 === count($users)) {
            $database->query('INSERT INTO `users`
                             (
                                 `email`,
                                 `password`,
                                 `power`
                             ) VALUES (
                                 "' . $_POST['email'] . '",
                                 "' . sha1($_POST['password']) . '",
                                 100
                             )
            ;');
        } else {
            if (in_array($_POST['email'], $emails)) {
                $messages[] = Page::error('An account with this email address already exists.', 'Invalid email address');
            } else {
                $database->query('INSERT INTO `users`
                                 (
                                     `email`,
                                     `password`
                                 ) VALUES (
                                     "' . $_POST['email'] . '",
                                     "' . sha1($_POST['password']) . '"
                                 )
                ;');

                $messages[] = Page::success('Your account was successfully created.', 'Success');
            }
        }
    } else {
        $messages[] = Page::error('<strong>' . $planetName . '</strong> is not a planet in our solar system. Read this for more information: <a href="https://www.space.com/16080-solar-system-planets.html" target="_blank">Solar system planets: Order of the 8 (or 9) planets</a>', 'Invalid planet');
    }
}

$page->header();
$page->navigation();
?>
<main>
    <div class="ui container">
        <h1 class="ui header"><?= $page->title ?></h1>

        <?= $page->messages($messages) ?>

        <div class="ui segment">
            <form class="ui form" method="post">
                <div class="ui divided relaxed stackable two column grid">

                    <div class=" row">
                        <div class="column">
                            <h2 class="ui header">Account details</h2>

                            <div class="field">
                                <label>Email</label>
                                <input type="email" name="email" placeholder="john.doe@domain.tld" />
                            </div>
                            <div class="field">
                                <label>Password</label>
                                <input type="password" name="password" />
                            </div>
                        </div>

                        <div class="column">
                            <h2 class="ui header">Authentication</h2>
                            <p>
                                Prove you are a Human, Lizard-person or Zuck-Like creature.
                                Please name a planet from our solar system.
                            </p>

                            <div class="field">
                                <label>Planet</label>
                                <input type="text" name="planet" />
                            </div>
                            <p>Robots are obivously from another solar system so this will keep them at bay.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column">
                            <div class="ui error message"></div>

                            <input class="ui primary button" type="submit" value="Register" />
                            <a class="ui tertiary button" href="/?page=login">Login</a>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
</main>
<?php
$page->footer();
