<?php

namespace wishthis;

use function Avifinfo\read;

class PageControllerWishlists extends PageController
{
    protected string $id = 'wishlists';

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = __('My lists');

        parent::__construct();
    }

    public function default(): void
    {
        $this->placeholders['WISHLIST_HEADING']         = __('Wishlist');
        $this->placeholders['WISHLIST_LOADING_HEADING'] = __('Loading your wishlists...');
        $this->placeholders['WISHLIST_OPTIONS_HEADING'] = __('Options');
        $this->placeholders['WISHLIST_OPTIONS_RENAME']  = __('Rename');
        $this->placeholders['WISHLIST_OPTIONS_DELETE']  = __('Delete');
        $this->placeholders['WISHLIST_SHARE_HEADING']   = __('Share');
        $this->placeholders['WISHLIST_SHARE_COPY_LINK'] = __('Copy');
        $this->placeholders['WISHLIST_ADD_WISH']        = __('Add a wish');
        $this->placeholders['WISHLIST_CREATE']          = __('Create a wishlist');
        $this->placeholders['WISHLIST_WISHES_HEADING']  = __('Wishes');

        \ob_start();
        require \ROOT . '/src/pages/parts/wishlist.php';
        $this->placeholders['WISHLIST_WISHES'] = \ob_get_clean();

        parent::render();
    }
}
