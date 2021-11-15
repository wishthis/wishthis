<?php

/**
 * register.php
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

use wishthis\Page;

$page = new page(__FILE__, 'Home');
$page->header();

?>
<main>
<section>
    <h1>Register</h1>

    <form method="post">
        <fieldset>
            <label>Email</label>
            <input type="email" name="email" placeholder="john.doe@domain.tld" />
        </fieldset>

        <fieldset>
            <label>Password</label>
            <input type="password" name="password" />
        </fieldset>

        <input type="submit" value="Register" />
    </form>
</section>
</main>

<?php
$page->footer();
