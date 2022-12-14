<?php

/**
 * Inline JavaScript
 */

namespace wishthis;

global $options;
?>

<script type="text/javascript">
    var wishthis = {};

    /** General */
    wishthis.version = '<?= VERSION ?>';
    wishthis.locale  = '<?= str_replace('_', '-', $this->language) ?>';
    wishthis.$_GET   = JSON.parse('<?= isset($_GET) ? json_encode($_GET) : json_encode(array()) ?>');

    /** Wish */
    wishthis.wish = {
        'no_image' : '<?= Wish::NO_IMAGE ?>',
        'status'   : {
            'temporary'   : '<?= Wish::STATUS_TEMPORARY ?>',
            'unavailable' : '<?= Wish::STATUS_UNAVAILABLE ?>',
            'fulfilled'   : '<?= Wish::STATUS_FULFILLED ?>',
        }
    }

    /** Strings */
    wishthis.strings = {
        'modal' : {
            'error' : {
                'title'   : '<?= __('Error') ?>',
                'content' : '<?= __('Something went wrong, that\'s all I know.') ?>',
                'approve' : '<?= __('Thanks for nothing') ?>',
            },
            'failure' : {
                'title'   : '<?= __('Unexpected output') ?>',
                'content' : [
                    '<?= __('Your request likely succeeded but the server did not confirm that the action was successful.') ?>',
                    '<?= __('For nerds: The API returned HTTP 200, there was no (error) output, yet the response is still not as expected.') ?>',
                    '<?= __('In any case, please report this.') ?>',
                ],
                'approve' : '<?= __('Ok then') ?>',
            },
            'success' : {
                'title' : '<?= __('Success') ?>',
            },

            'wishlist' : {
                'warning' : {
                    'approve' : '<?= __('Close this tab') ?>',
                    'deny'    : '<?= __('Show wishlist anyway') ?>',
                },
                'delete' : {
                    'title'   : '<?= __('Really delete?') ?>',
                    'content' : '<?= sprintf(__('Do you really want to delete the wishlist %s?'), '<strong>WISHLIST_NAME</strong>') ?>',
                    'approve' : '<?= __('Yes, delete') ?>',
                    'deny'    : '<?= __('No, keep') ?>',
                },
            },

            'wish' : {
                'delete' : {
                    'title'   : '<?= __('Really delete?') ?>',
                    'content' : '<?= __('Would you really like to delete to this wish? It will be gone forever.') ?>',
                    'approve' : '<?= __('Yes, delete') ?>',
                    'deny'    : '<?= __('No, keep') ?>',
                }
            }
        },

        'form' : {
            'profile' : {
                'password' : '<?= __('Passwords must match.') ?>',
            },
            'prompt' : {
                'empty'                : '<?= __('{name} must have a value') ?>',
                'checked'              : '<?= __('{name} must be checked') ?>',
                'email'                : '<?= __('{name} must be a valid e-mail') ?>',
                'url'                  : '<?= __('{name} must be a valid URL') ?>',
                'regExp'               : '<?= __('{name} is not formatted correctly') ?>',
                'integer'              : '<?= __('{name} must be an integer') ?>',
                'decimal'              : '<?= __('{name} must be a decimal number') ?>',
                'number'               : '<?= __('{name} must be set to a number') ?>',
                'is'                   : '<?= __('{name} must be "{ruleValue}"') ?>',
                'isExactly'            : '<?= __('{name} must be exactly "{ruleValue}"') ?>',
                'not'                  : '<?= __('{name} cannot be set to "{ruleValue}"') ?>',
                'notExactly'           : '<?= __('{name} cannot be set to exactly "{ruleValue}"') ?>',
                'contain'              : '<?= __('{name} cannot contain "{ruleValue}"') ?>',
                'containExactly'       : '<?= __('{name} cannot contain exactly "{ruleValue}"') ?>',
                'doesntContain'        : '<?= __('{name} must contain "{ruleValue}"') ?>',
                'doesntContainExactly' : '<?= __('{name} must contain exactly "{ruleValue}"') ?>',
                'minLength'            : '<?= __('{name} must be at least {ruleValue} characters') ?>',
                'length'               : '<?= __('{name} must be at least {ruleValue} characters') ?>',
                'exactLength'          : '<?= __('{name} must be exactly {ruleValue} characters') ?>',
                'maxLength'            : '<?= __('{name} cannot be longer than {ruleValue} characters') ?>',
                'match'                : '<?= __('{name} must match {ruleValue} field') ?>',
                'different'            : '<?= __('{name} must have a different value than {ruleValue} field') ?>',
                'creditCard'           : '<?= __('{name} must be a valid credit card number') ?>',
                'minCount'             : '<?= __('{name} must have at least {ruleValue} choices') ?>',
                'exactCount'           : '<?= __('{name} must have exactly {ruleValue} choices') ?>',
                'maxCount'             : '<?= __('{name} must have {ruleValue} or less choices') ?>',
            }
        },

        'toast' : {
            'wishlist' : {
                'rename' : '<?= __('Wishlist successfully renamed.') ?>',
                'delete' : '<?= __('Wishlist successfully deleted.') ?>',
            },

            'wish' : {
                'create' : '<?= __('Wish successfully created.') ?>',
                'update' : '<?= __('Wish information updated.') ?>',
                'delete' : '<?= __('Wish successfully deleted.') ?>',
            },

            'clipboard' : {
                'error' : {
                    'title'   : '<?= __('Error') ?>',
                    'content' : '<?= __('Unable to copy to clipboard. There is likely a permission issue.') ?>',
                },
                'success' : {
                    'content' : '<?= __('Link copied to clipboard.') ?>',
                },
            },
        },

        'calendar' : {
            'today'   : '<?= _x('Today', 'Calendar') ?>',
            'now'     : '<?= _x('Now', 'Calendar') ?>',
            'am'      : '<?= _x('AM', 'Calendar') ?>',
            'pm'      : '<?= _x('PM', 'Calendar') ?>',
            'week_no' : '<?= _x('Week', 'Calendar') ?>',
        },

        'button' : {
            'wishlist' : {
                'remember' : '<?= __('Remember list') ?>',
                'forget'   : '<?= __('Forget list') ?>',
            }
        },

        'message' : {
            'wishlist' : {
                'empty' : {
                    'header'  : '<?= __('Empty') ?>',
                    'content' : '<?= __('This wishlist seems to be empty.') ?>',
                }
            }
        },

        'popup' : {
            'last_modified' : '<?= __('Last modified') ?>',
        }
    }

    /**
     * Fomantic UI
     */
    <?php
    $api_urls = array(
        'get wishlists'             => '/?page=api&module=wishlists',
        'get wishlists by priority' => '/?page=api&module=wishlists&style={style}&wishlist_id={wishlistid}&priority={priority}',
        'delete wishlist'           => '/?page=api&module=wishlists',
        'update wish status'        => '/?page=api&module=wishes',
        'delete wish'               => '/?page=api&module=wishes&wish_id={wishid}',
    );
    ?>

    document.addEventListener('DOMContentLoaded', function() {
        /** API */
        $.fn.api.settings.api = {
            <?php
            foreach ($api_urls as $action => $url) {
                $url    = new URL($url);
                $pretty = $url->getPretty();

                echo '\'' . $action . '\' : \'' . $pretty . '\',' . PHP_EOL;
            }
            ?>
        };
    });

</script>
