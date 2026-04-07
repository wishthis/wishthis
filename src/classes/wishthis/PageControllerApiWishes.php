<?php

namespace wishthis;

class PageControllerApiWishes extends PageController
{
    protected string $id                   = 'api-wishes';
    protected bool $requiresAuthentication = false;

    private Wishlist $wishlist;

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = 'API';

        if (isset($parameters['id'])) {
            $this->wishlist = Wishlist::getFromId($parameters['id']);
        }

        if (isset($parameters['hash'])) {
            $this->wishlist = Wishlist::getFromHash($parameters['hash']);
        }

        parent::__construct();
    }

    public function get(): void
    {
        \ob_start();
        \parse_str($_SERVER['QUERY_STRING'], $arguments);

        $wishlist     = $this->wishlist;
        $wishlistId   = $wishlist->getId();
        $wishPriority = (int) $arguments['priority'];

        $options = [
            'style'        => $arguments['style'],
            'placeholders' => [
                'wishlist_id'   => $wishlistId,
                'wish_priority' => $wishPriority,
            ],
        ];
        $where   = [
            'wishlist' => '`wishlist` = :wishlist_id',
            'priority' => '`priority` = :wish_priority',
        ];

        if ('-1' === $arguments['priority']) {
            unset($options['placeholders']['wish_priority']);
            unset($where['priority']);
        }

        if (0 === $wishPriority) {
            unset($options['placeholders']['wish_priority']);
            $where['priority'] = '`priority` IS NULL OR `priority` = 0';
        }

        $options['WHERE'] = \sprintf('(%1$s)', \implode(') AND (', $where));

        $response['results'] = $wishlist->getCards($options);

        $response['warning'] = \ob_get_clean();
        $response['success'] = true;

        \header('Content-type: application/json; charset=utf-8');
        echo \json_encode($response);
    }
}
