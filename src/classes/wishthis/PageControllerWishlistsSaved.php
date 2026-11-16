<?php

namespace wishthis;

class PageControllerWishlistsSaved extends PageController
{
    protected string $id = 'wishlists-saved';

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = __('Remembered lists');

        parent::__construct();
    }

    public function default(): void
    {
        $user = User::getCurrent();

        $wishlistsSaved  = $user->getSavedWishlists();
        $wishlistsByUser = [];

        foreach ($wishlistsSaved as $wishlistSaved) {
            $wishlistOwnerId = $wishlistSaved['user'];

            $wishlistsByUser[$wishlistOwnerId][] = $wishlistSaved;
        }

        $noImagePlaceholder = \file_get_contents(ROOT . '/' . Wish::NO_IMAGE);

        \ob_start();

        if (!empty($wishlistsByUser)) {
            foreach ($wishlistsByUser as $wishlistUserId => $wishlistsSaved) {
                $wishlistUser     = User::getFromID($wishlistUserId);
                $wishlistUserName = $wishlistUser->getDisplayName();
                ?>
                <h2 class="ui header"><?= $wishlistUserName ?></h2>

                <div class="ui four column doubling stackable grid wishlists-saved">
                    <?php
                    foreach ($wishlistsSaved as $wishlistSaved) {
                        $wishlistSavedWishlistId = $wishlistSaved['wishlist'];

                        $wishlist     = Wishlist::getFromId($wishlistSavedWishlistId);
                        $wishlistHash = $wishlist->getHash();
                        $wishlistHref = \sprintf(
                            '%1$s/%2$s',
                            Page::PAGE_WISHLIST,
                            $wishlistHash
                        );
                        $wishlistName = $wishlist->getTitle();
                        ?>
                        <div class="column">
                            <a class="header" href="<?= $wishlistHref ?>">
                                <div class="ui rounded bordered fluid image">
                                    <?= $noImagePlaceholder ?>
                                </div>
                            </a>

                            <div class="content">
                                <a class="header" href="<?= $wishlistHref ?>"><?= $wishlistName ?></a>
                                <div class="description"><?= $wishlistUserName ?></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        } else {
            $messageContent = __('Ask somebody to share their wishlist with you and hit the remember button for it to show up here!');
            $messageHeader  = __('No lists');
            $messageType    = MessageType::INFO;
            $message        = new Message(
                $messageContent,
                $messageHeader,
                $messageType
            );

            $_SESSION['messages'][] = $message;
        }

        $this->placeholders['REMEMBERED_LISTS_HTML'] = \ob_get_clean();

        parent::render();
    }
}
