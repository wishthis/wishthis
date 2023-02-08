<?php

/**
 * Wishes
 *
 * @category API
 */

namespace wishthis;

global $page, $database;

if (!isset($page)) {
    http_response_code(403);
    die('Direct access to this location is not allowed.');
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['wish_id'])) {
            $wish = new Wish($_GET['wish_id'], true);

            $response['info'] = $wish;

            if (isset($_GET['wishlist_user'])) {
                $response['html'] = $wish->getCard($_GET['wishlist_user']);
            }
        } elseif (isset($_GET['wish_url'])) {
            $url   = trim($_GET['wish_url']);
            $cache = new Cache\Embed($url);
            $info  = $cache->get(true);

            if (isset($info->url) && $info->url) {
                $code = URL::getResponseCode($info->url);

                if ($code < 200 || $code >= 400) {
                    $info->url = $url;
                }
            }

            $response = array(
                'info' => $info,
            );
        } elseif (isset($_GET['wishlist_id'], $_GET['wishlist_style'], $_GET['wish_priority'])) {
            /**
             * Get wishes by priority
             */
            $wishlist                                 = new Wishlist($_GET['wishlist_id']);
            $options                                  = array(
                'style' => $_GET['wishlist_style'],
            );
            $where                                    = array(
                'priority' => '`priority` = :wish_priority',
            );
            $options['placeholders']['wish_priority'] = $_GET['wish_priority'];

            if (-1 === intval($_GET['wish_priority'])) {
                unset($where['priority']);
                unset($options['placeholders']['wish_priority']);
            }

            if (empty($_GET['wish_priority'])) {
                $where['priority'] = '`priority` IS NULL';
            }

            $options['WHERE'] = '(' . implode(') AND (', $where) . ')';

            $wishes = array_map(
                function (Wish $wish) use ($wishlist) {
                    $wish->card = $wish->getCard($wishlist->user);

                    return $wish;
                },
                $wishlist->getWishes($options)
            );

            $response['results'] = $wishes;
        }
        break;

    case 'POST':
        if (isset($_POST['wishlist_id']) || isset($_POST['wish_id'])) {
            /**
             * Save wish
             */
            if (
                   empty($_POST['wish_title'])
                && empty($_POST['wish_description'])
                && empty($_POST['wish_url'])
            ) {
                break;
            }

            $wish_title          = Sanitiser::getTitle($_POST['wish_title']);
            $wish_description    = Sanitiser::getText($_POST['wish_description']);
            $wish_image          = Sanitiser::getURL($_POST['wish_image']);
            $wish_url            = Sanitiser::getURL($_POST['wish_url']);
            $wish_priority       = !empty(Sanitiser::getNumber($_POST['wish_priority'])) ? Sanitiser::getNumber($_POST['wish_priority']) : null;
            $wish_is_purchasable = isset($_POST['wish_is_purchasable']);

            if (Wish::NO_IMAGE === $wish_image) {
                $wish_image = '';
            }

            if (isset($_POST['wish_id'], $_POST['wishlist_id'])) {
                /** Update wish */
                $wish = new Wish($_POST['wish_id']);

                /** Update wish information */
                if (!empty($wish_url)) {
                    $cache = new Cache\Embed($wish_url);
                    $info  = $cache->get(true);

                    if (empty($wish_title) && empty($wish->title)) {
                        $wish_title = Sanitiser::getTitle($info->title);
                    }

                    if (empty($wish_description) && empty($wish->description)) {
                        $wish_description = Sanitiser::getText($info->description);
                    }

                    /** Image */
                    if (empty($wish_image) && empty($wish->image)) {
                        if (!empty($info->image)) {
                            $codeImage = URL::getResponseCode($info->image);

                            if ($codeImage >= 200 && $codeImage < 400) {
                                $wish_image = '"' . $info->image . '"';
                            }
                        }
                    }

                    $response = array(
                        'info' => $info,
                    );
                }

                /** Update */
                $wish_title       = empty($wish_title)                                   ? null : substr($wish_title, 0, 128);
                $wish_description = empty($wish_description)                             ? null : $wish_description          ;
                $wish_image       = empty($wish_image) || Wish::NO_IMAGE === $wish_image ? null : $wish_image                ;
                $wish_url         = empty($wish_url)                                     ? null : $wish_url                  ;

                $database
                ->query(
                    'UPDATE `wishes`
                        SET `wishlist`       = :wishlist_id,
                            `title`          = :wish_title,
                            `description`    = :wish_description,
                            `image`          = :wish_image,
                            `url`            = :wish_url,
                            `priority`       = :wish_priority,
                            `is_purchasable` = :wish_is_purchasable
                      WHERE `id`             = :wish_id',
                    array(
                        'wishlist_id'         => $wish->wishlist,
                        'wish_title'          => $wish_title,
                        'wish_description'    => $wish_description,
                        'wish_image'          => $wish_image,
                        'wish_url'            => $wish_url,
                        'wish_priority'       => $wish_priority,
                        'wish_is_purchasable' => $wish_is_purchasable,
                        'wish_id'             => $wish->id,
                    )
                );

                /**
                 * Product
                 */
                $wish_price = empty($_POST['wish_price']) || 'false' === $wish_is_purchasable
                            ? null
                            : Sanitiser::getNumber($_POST['wish_price']);

                $database
                ->query(
                    'REPLACE INTO `products`
                    (
                        `wish`,
                        `price`
                    ) VALUES (
                        :wish_id,
                        :wish_price
                    );',
                    array(
                        'wish_id'    => $wish->id,
                        'wish_price' => $wish_price,
                    )
                );

                $response['lastInsertId'] = $wish->id;
            } elseif (isset($_POST['wishlist_id'])) {
                /** Insert wish */
                $wishlist_id = $_POST['wishlist_id'];

                /** Update wish information */
                if (!empty($wish_url)) {
                    $cache = new Cache\Embed($wish_url);
                    $info  = $cache->get(true);

                    if (empty($wish_title) && isset($info->title)) {
                        $wish_title = Sanitiser::getTitle($info->title);
                    }

                    if (empty($wish_description) && isset($info->description)) {
                        $wish_description = Sanitiser::getText($info->description);
                    }

                    /** Image */
                    if (empty($wish_image) && empty($wish->image)) {
                        if (!empty($info->image)) {
                            $codeImage = URL::getResponseCode($info->image);

                            if ($codeImage >= 200 && $codeImage < 400) {
                                $wish_image = $info->image;
                            }
                        }
                    }

                    $response = array(
                        'info' => $info,
                    );
                }

                /** Update */
                $wish_title       = empty($wish_title)                                   ? null : substr($wish_title, 0, 128);
                $wish_description = empty($wish_description)                             ? null : $wish_description          ;
                $wish_image       = empty($wish_image) || Wish::NO_IMAGE === $wish_image ? null : $wish_image                ;
                $wish_url         = empty($wish_url)                                     ? null : $wish_url                  ;

                $database
                ->query(
                    'INSERT INTO `wishes`
                    (
                        `wishlist`,
                        `title`,
                        `description`,
                        `image`,
                        `url`,
                        `priority`,
                        `is_purchasable`
                    ) VALUES (
                         :wishlist_id,
                         :wish_title,
                         :wish_description,
                         :wish_image,
                         :wish_url,
                         :wish_priority,
                         :wish_is_purchasable
                    );',
                    array(
                        'wishlist_id'         => $wishlist_id,
                        'wish_title'          => $wish_title,
                        'wish_description'    => $wish_description,
                        'wish_image'          => $wish_image,
                        'wish_url'            => $wish_url,
                        'wish_priority'       => $wish_priority,
                        'wish_is_purchasable' => $wish_is_purchasable,
                    )
                );

                /**
                 * Product
                 */
                $wish_id    = $database->lastInsertId();
                $wish_price = Sanitiser::getNumber($_POST['wish_price']);

                if ($wish_price > 0) {
                    $database
                    ->query(
                        'INSERT INTO `products`
                        (
                            `wish`,
                            `price`
                        ) VALUES (
                            :wish_id,
                            :wish_price
                        );',
                        array(
                            'wish_id'    => $wish_id,
                            'wish_price' => $wish_price,
                        )
                    );
                }

                $response['lastInsertId'] = $wish_id;
            }
        }
        break;

    case 'PUT':
        $_PUT = $this->input;

        if (isset($_PUT['wish_id'], $_PUT['wish_status'])) {
            /**
             * Update Wish Status
             */
            $wish_status = Sanitiser::getStatus($_PUT['wish_status']);
            $wish_id     = Sanitiser::getNumber($_PUT['wish_id']);

            if (Wish::STATUS_TEMPORARY === $wish_status) {
                $wish_status = time();
            }

            $database->query(
                'UPDATE `wishes`
                    SET `status` = :wish_status
                  WHERE `id`     = :wish_id',
                array(
                    'wish_status' => $wish_status,
                    'wish_id'     => $wish_id,
                )
            );

            $response['success'] = true;
        } elseif (isset($_PUT['wish_url_current'], $_PUT['wish_url_proposed'])) {
            /**
             * Update Wish URL
             */
            $database->query(
                'UPDATE `wishes`
                    SET `url` = :wish_url_proposed,
                  WHERE `url` = :wish_url_current',
                array(
                    'wish_url_proposed' => Sanitiser::getURL($_PUT['wish_url_proposed']),
                    'wish_url_current'  => Sanitiser::getURL($_PUT['wish_url_current']),
                )
            );

            $response['success'] = true;
        }
        break;

    case 'DELETE':
        $_DELETE = $this->input;

        if (isset($_DELETE['wish_id'])) {
            $database->query(
                'DELETE FROM `wishes`
                       WHERE `id` = :wish_id',
                array(
                    'wish_id' => Sanitiser::getNumber($_DELETE['wish_id']),
                )
            );

            $response['success'] = true;
        }

        break;
}
