<?php

/**
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

?>

<div class="ui secondary menu wish-add">
    <a class="item active"   data-tab="general"><?= __('General') ?></a>
    <a class="item disabled" data-tab="product"><?= __('Product') ?></a>
</div>

<div class="ui tab active" data-tab="general">
    <div class="ui two column grid">
        <div class="stackable row">
            <div class="column">

                <div class="field">
                    <label><?= __('Title') ?></label>

                    <div class="ui input">
                        <input type     ="text"
                               name     ="wish_title"
                               maxlength="128"
                        />
                    </div>
                </div>

                <div class="field">
                    <label><?= __('Description') ?></label>

                    <div class="ui input">
                        <textarea name="wish_description"></textarea>
                    </div>
                </div>

            </div>

            <div class="column">

                <div class="field">
                    <label><?= __('URL') ?></label>

                    <input type     ="url"
                           name     ="wish_url"
                           maxlength="255"
                    />
                </div>

                <div class="field">
                    <label><?= __('Priority') ?></label>

                    <select class="ui selection clearable dropdown priority"
                            name ="wish_priority"
                    >
                        <option value=""><?= __('Select priority') ?></option>

                        <?php foreach (Wish::$priorities as $priority => $item) { ?>
                            <option value="<?= $priority ?>"><?= $item['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="field">
                    <label><?= __('Image') ?></label>

                    <input type     ="url"
                           name     ="wish_image"
                           maxlength="255"
                    />
                </div>

                <div class="grouped fields">
                    <label><?= __('Properties') ?></label>

                    <div class="field">
                        <div class="ui checkbox wish-is-purchasable">

                            <input type="checkbox"
                                   name="wish_is_purchasable"
                            />
                            <label><?= __('Is purchasable') ?></label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="ui tab" data-tab="product">
    <div class="ui two column grid">
        <div class="stackable row">
            <div class="column">

                <div class="field">
                    <label><?= __('Price') ?></label>

                    <input type     ="text"
                           name     ="wish_price"
                           maxlength="9"
                    />
                </div>

            </div>
        </div>
    </div>
</div>
