<?php

namespace wishthis;

class PageControllerWishlist extends PageController
{
    protected string $id                   = 'wishlist';
    protected bool $requiresAuthentication = false;

    private Wishlist $wishlist;

    public function __construct(array $parameters = [])
    {
        $this->wishlist = Wishlist::getFromHash($parameters['hash']);

        if (false === $this->wishlist) {
            \http_response_code(404);
            die();
        }

        $this->pageTitle = $this->wishlist->getTitle();

        parent::__construct();
    }

    public function default(): void
    {
        $user                 = User::getCurrent();
        $userIsLoggedIn       = $user->isLoggedIn();
        $userId               = $userIsLoggedIn ? $user->getId() : -1;
        $userIsViewingOwnList = $userId === $this->wishlist->getUserId();

        $this->placeholders['WISHLIST_HASH'] = $this->wishlist->getHash();

        $this->placeholders['REMEMBER_LIST_HTML'] = '';

        if ($userIsLoggedIn) {
            \ob_start();
            ?>
            <div class="ui stackable grid">
                <div class="column">
                    <button class="ui white small basic labeled icon button save disabled loading">
                        <i class="heart icon"></i>
                        <span><?= __('Remember list') ?></span>
                    </button>
                </div>
            </div>
            <?php
            $this->placeholders['REMEMBER_LIST_HTML'] = \ob_get_clean();
        }

        $this->placeholders['VIEWING_OWN_LIST_WARNING_HTML'] = '';

        if ($userIsViewingOwnList) {
            \ob_start();
            ?>
            <div class="ui icon warning message wishlist-own">
                <i class="exclamation triangle icon"></i>
                <div class="content">
                    <div class="header">
                        <?= __('Careful') ?>
                    </div>
                    <div class="text">
                        <p><?= __('You are viewing your own wishlist! You will be able to see which wishes have already been fulfilled for you. Don\'t you want to be surprised?') ?></p>
                        <p><?= __('It\'s probably best to just close this tab.') ?></p>
                    </div>
                </div>
            </div>
            <?php
            $this->placeholders['VIEWING_OWN_LIST_WARNING_HTML'] = \ob_get_clean();
        }

        $this->placeholders['WHAT_TO_DO_HEADING']     = __('What to do?');
        $this->placeholders['WHAT_TO_DO_DESCRIPTION'] = \sprintf(
            /** TRANSLATORS: %1$s: Details, %2$s: Fulfill wish */
            __('If you found a wish you would like to fulfil, open the wish %1$s and then click the %2$s button and it will be unavailable for everybody else.'),
            \sprintf(
                '<span class="ui tiny horizontal label"><i class="stream icon"></i> %1$s</span>',
                __('Details')
            ),
            \sprintf(
                '<span class="ui primary tiny horizontal label"><i class="gift icon"></i> %1$s</span>',
                __('Fulfil wish')
            )
        );
        $this->placeholders['WISHLIST_WISHES_HEADING']      = __('Wishes');
        $this->placeholders['WISHLIST_WISHES_REQUEST_MORE'] = __('Request more wishes');

        \ob_start();
        require \ROOT . '/src/pages/parts/wishlist.php';
        $this->placeholders['WISHLIST_WISHES'] = \ob_get_clean();

        parent::render();
    }
}
