<!-- catalog -->
<?php if (!empty($categories)) { ?>
    <div class="catalog-categories">
        <?php foreach ($categories as $category) { ?>
            <a href="<?= $category['url'] ?>" class="button second-button transparent-bg small-button small-padding"><?= $category['title'] ?></a>
        <?php } ?>
        <div data-click-categories='0' data-outer-hide='0' data-rotate='.arrow-icon' data-hidden='hidden-categories' data-content='#hidden_categories' class='display-categories-button pointer hover dropbox-toogle'>
            <img class="arrow-icon" src='/themes/purple/assets/images/arrow-down.svg'>
        </div>
        <div id='hidden_categories' class="hidden-block-categories flex flex-start flex-wrap gap-10 mb10"></div>
    </div>
<?php } ?>
<div class="align-start">
    <div class="catalog-filters hidden-catalog-filter">
        <?php if ($is_service_catalog === true) { ?>
        <div id="filter1" class="catalog-filter-item">
            <div data-outer-hide="0" data-content="#filter1 .filter-content" data-hidden="hidden-filter" data-rotate=".arrow-icon" class="filter-title dropbox-toogle">
                <div><?= t('service_price') ?></div>
                <span class="faq-arrow-icon arrow-icon"><i class="fa-solid fa-chevron-down"></i></span>
            </div>

            <div class="filter-price filter-content additional-filter-box hidden-filter">
                <input type="text" name="price_from" placeholder="<?= mb_ucfirst(t('from')) ?>" class="text-input small-padding input-filter-js money">
                <input type="text" name="price_to" placeholder="<?= mb_ucfirst(t('to')) ?>" class="text-input small-padding input-filter-js money">
                <span class="money-symbol"><?= $currency ?></span>
            </div>
        </div>

        <div id="filter2" class="catalog-filter-item">
            <div data-outer-hide="0" data-content="#filter2 .filter-content" data-hidden="hidden-filter" data-rotate=".arrow-icon" class="filter-title dropbox-toogle">
                <div class="filter-title"><?= t('service_duration') ?></div>
                <span class="faq-arrow-icon arrow-icon"><i class="fa-solid fa-chevron-down"></i></span>
            </div>

            <div class="catalog-filter-time filter-content additional-filter-box hidden-filter">
                <input type="radio" name="service_duration" value="1" id="item1" class='hidden radio-filter-js'>
                <input type="radio" name="service_duration" value="2" id="item2" class='hidden radio-filter-js'>
                <input type="radio" name="service_duration" value="3" id="item3" class='hidden radio-filter-js'>
                <input type="radio" name="service_duration" value="4" id="item4" class='hidden radio-filter-js'>
                <div class="catalog-filter-line">
                    <div class="duration-range-icons">
                        <label for="item1">
                            <img src="/themes/purple/assets/images/checked.svg">
                        </label>
                        <label for="item2">
                            <img src="/themes/purple/assets/images/checked.svg">
                        </label>
                        <label for="item3">
                            <img src="/themes/purple/assets/images/checked.svg">
                        </label>
                        <label for="item4">
                            <img src="/themes/purple/assets/images/checked.svg">
                        </label>
                    </div>
                    <div class="duration-range-item">
                        <div class="duration-range-line"></div>
                    </div>
                    <div class="duration-range-item">
                        <div class="duration-range-line"></div>
                    </div>
                    <div class="duration-range-item">
                        <div class="duration-range-line"></div>
                    </div>
                </div>
                <div class="duration-range-texts">
                    <label for="item1">30<?= mb_strtolower(mb_substr(t('minuts'), 0, 1)) ?></label>
                    <label for="item2">1-1,5<?= mb_strtolower(mb_substr(t('hours'), 0, 1)) ?></label>
                    <label for="item3">2-3<?= mb_strtolower(mb_substr(t('hours'), 0, 1)) ?></label>
                    <label for="item4">3<?= mb_strtolower(mb_substr(t('hours'), 0, 1)) ?>+</label>
                </div>
            </div>
        </div>
        <?php } ?>

        <div id="filter3" class="catalog-filter-item">
            <div data-outer-hide="0" data-content="#filter3 .filter-content" data-hidden="hidden-filter" data-rotate=".arrow-icon" class="filter-title dropbox-toogle">
                <div class="filter-title"><?= t('geolocation') ?></div>
                <span class="faq-arrow-icon arrow-icon"><i class="fa-solid fa-chevron-down"></i></span>
            </div>

            <div class="catalog-filter-geolocation filter-content additional-filter-box hidden-filter">
                <input type="text" placeholder="<?= t('city') ?>" name="city"  class="text-input small-padding input-filter-js">
                <input type="text" placeholder="<?= t('street_and_area') ?>" name="street" class="text-input small-padding input-filter-js">
            </div>
        </div>

        <?php if ($is_service_catalog === true) { ?>
        <div id="filter4" class="catalog-filter-item">
            <div data-outer-hide="0" data-content="#filter4 .filter-content" data-hidden="hidden-filter" data-rotate=".arrow-icon" class="filter-title dropbox-toogle">
                <div class="filter-title"><?= t('free_dates') ?></div>
                <span class="faq-arrow-icon arrow-icon"><i class="fa-solid fa-chevron-down"></i></span>
            </div>

            <div class="catalog-filter-date filter-content additional-filter-box hidden-filter">
                <div class="input-and-button">
                    <input name="date_range" type="text" placeholder="<?= t('date_range') ?>" class="text-input small-padding input-filter-js">
                    <button class="button primary-button calendar-button"><img src="/themes/purple/assets/images/calendar.svg"></button>
                </div>
                <div class="filter-time-period">
                    <select name="time_from" class="select-filter-js">
                        <option disabled selected><?= mb_ucfirst(t('from_time')) ?></option>
                        <option value="8">8:00</option>
                        <option value="9">9:00</option>
                        <option value="10">10:00</option>
                        <option value="11">11:00</option>
                        <option value="12">12:00</option>
                        <option value="13">13:00</option>
                        <option value="14">14:00</option>
                        <option value="15">15:00</option>
                        <option value="16">16:00</option>
                        <option value="17">17:00</option>
                        <option value="18">18:00</option>
                        <option value="19">19:00</option>
                        <option value="20">20:00</option>
                        <option value="21">21:00</option>
                        <option value="22">22:00</option>
                    </select>
                    <select name="time_to" class="select-filter-js">
                        <option disabled selected><?= mb_ucfirst(t('to')) ?></option>
                        <option value="8">8:00</option>
                        <option value="9">9:00</option>
                        <option value="10">10:00</option>
                        <option value="11">11:00</option>
                        <option value="12">12:00</option>
                        <option value="13">13:00</option>
                        <option value="14">14:00</option>
                        <option value="15">15:00</option>
                        <option value="16">16:00</option>
                        <option value="17">17:00</option>
                        <option value="18">18:00</option>
                        <option value="19">19:00</option>
                        <option value="20">20:00</option>
                        <option value="21">21:00</option>
                        <option value="22">22:00</option>
                    </select>
                </div>
            </div>
        </div>
        <?php } ?>

        <div id="filter5" class="catalog-filter-item">
            <div data-outer-hide="0" data-content="#filter5 .filter-content" data-hidden="hidden-filter" data-rotate=".arrow-icon" class="filter-title dropbox-toogle">
                <div class="filter-title"><?= t('rating') ?></div>
                <span class="faq-arrow-icon arrow-icon"><i class="fa-solid fa-chevron-down"></i></span>
            </div>

            <div class="catalog-filter-stars filter-content additional-filter-box hidden-filter">
                <label class="checkbox-block">
                    <input type="radio" name="rating" value="5" class="checkbox-filter-js" id="">
                    <span class="stars-rating"><span class="star-active"><i class="fa-solid fa-star"></i></span><span class="star-active"><i class="fa-solid fa-star"></i></span><span class="star-active"><i class="fa-solid fa-star"></i></span><span class="star-active"><i class="fa-solid fa-star"></i></span><span class="star-active"><i class="fa-solid fa-star"></i></span></span>
                </label>
                <label class="checkbox-block flex flex-start mt5 mb5">
                    <input value="4" name="rating" class="radio-filter-js" type="radio" id="">
                    <span class="stars-rating"><span class="star-active"><i class="fa-solid fa-star"></i></span><span class="star-active"><i class="fa-solid fa-star"></i></span><span class="star-active"><i class="fa-solid fa-star"></i></span><span class="star-active"><i class="fa-solid fa-star"></i></span></span>
                </label>
                <label class="checkbox-block flex flex-start mt5 mb5">
                    <input value="3" name="rating" class="radio-filter-js" type="radio" id="">
                    <span class="stars-rating"><span class="star-active"><i class="fa-solid fa-star"></i></span><span class="star-active"><i class="fa-solid fa-star"></i></span><span class="star-active"><i class="fa-solid fa-star"></i></span></span>
                </label>
                <label class="checkbox-block flex flex-start mt5 mb5">
                    <input value="2" name="rating" class="radio-filter-js" type="radio" id="">
                    <span class="stars-rating"><span class="star-active"><i class="fa-solid fa-star"></i></span><span class="star-active"><i class="fa-solid fa-star"></i></span></span>
                </label>
                <label class="checkbox-block flex flex-start mt5 mb5">
                    <input value="1" name="rating" class="radio-filter-js" type="radio" id="">
                    <span class="stars-rating"><span class="star-active"><i class="fa-solid fa-star"></i></span></span>
                </label>
            </div>
        </div>

        <?php if ($is_service_catalog === true) { ?>
        <div id="filter6" class="catalog-filter-item">
            <div data-outer-hide="0" data-content="#filter6 .filter-content" data-hidden="hidden-filter" data-rotate=".arrow-icon" class="filter-title dropbox-toogle">
                <div class="filter-title"><?= t('discounts') ?></div>
                <span class="faq-arrow-icon arrow-icon"><i class="fa-solid fa-chevron-down"></i></span>
            </div>

            <div class="checkbox-discount filter-content additional-filter-box hidden-filter">
                <label class="checkbox-block checkbox-discount">
                    <input class="checkbox-filter-js" type="checkbox" name="discounts" value="1">
                    <?= t('active_discounts_exists') ?>
                </label>
            </div>
        </div>
        <?php } ?>

    </div>
    <div class="catalog-items">
        <div class="catalog-sort-options">
            <div class="catalog-sort-filter-recommendation">
                <div class="filter-recommendation">
                    <span class="order-by-text"><?= t('order_by') ?>:</span>
                    <div data-active-class='active-sort-item' class="recommendation-list change-list-js">
                        <a data-name="sort" data-query="recommended" class="sort-item active-sort-item button-filter-js change-list-item-js"><?= t('by_recomended') ?></a>
                        <a data-name="sort" data-query="user_rating" class="sort-item button-filter-js change-list-item-js"><?= t('by_rating') ?></a>
                        <?php if ($is_service_catalog === true) { ?>
                        <a data-name="sort" data-query="price" class="sort-item button-filter-js change-list-item-js"><?= t('by_price') ?></a>
                        <a data-name="sort" data-query="discount" class="sort-item button-filter-js change-list-item-js"><?= t('by_discount') ?></a>
                        <a data-name="sort" data-query="duration" class="sort-item button-filter-js change-list-item-js"><?= t('by_time') ?></a>
                        <?php } ?>
                        <div data-click='0' data-outer-hide='0' data-rotate='.arrow-icon' data-hidden='hidden-recommendation' data-content='#hidden_recommendation' class='display-recommendation-button pointer hover dropbox-toogle'>
                            <img class="arrow-icon" src='/themes/purple/assets/images/arrow-down.svg'>
                        </div>
                        <div id='hidden_recommendation' class="hidden-recommendation flex flex-start flex-wrap gap-10 mb10"></div>
                    </div>
                </div>
                <div class="filter-master-salon">
                    <label class="checkbox-block">
                        <input name="view" value="master" class="radio-filter-js" type="radio" id="">
                        <?= t('master') ?>
                    </label>
                    <label class="checkbox-block">
                        <input name="view" value="salon" class="radio-filter-js" type="radio" id="">
                        <?= t('salon') ?>
                    </label>
                </div>
                <a href="#" class="catalog-items-filter-btn dropbox-toogle" data-click='0' data-hidden='hidden-catalog-filter' data-close="true" data-content='.catalog-filters'>
                    <img class="btn-filter-open" src="/themes/purple/assets/images/filter.svg" alt="filter">
                </a>
            </div>
        </div>
        <div class="catalog-content" id="filters_content_js">

            <?php if (!empty($sellers)) {
                foreach ($sellers as $seller) {
                    echo $seller;
                }
            } else {
                echo '<p class="catalog-no-results">' . t('no_results') . '</p>';
            } ?>
        </div>

        <?php if (!empty($pagination['items']) and count($pagination['items']) > 1) { ?>
            <div class="catalog-pagination">
                <a class='pag-icon hover' href="<?= $pagination['prev_page'] ?>"><i class="fa-solid fa-chevron-left"></i></a>
                <div class="flex">
                    <?php foreach ($pagination['items'] as $pagItem) { ?>
                        <a href="<?= $pagItem['href'] ?>" class='pag-item <?= $pagItem['class'] ?>'><?= $pagItem['num'] ?></a>
                    <?php } ?>
                </div>
                <a class='pag-icon hover' href="<?= $pagination['next_page'] ?>"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
        <?php } ?>
    </div>
</div>
<form action="" method="post" class="filters-form-js">
    <input type="hidden" name="filter" value="1">
    <input type="hidden" name="city">
    <input type="hidden" name="street">
    <input type="hidden" name="rating">
    <input type="hidden" name="sort">
    <input type="hidden" name="view">
    <?php if ($is_service_catalog === true) { ?>
        <input type="hidden" name="discounts">
        <input type="hidden" name="service_duration">
        <input type="hidden" name="date_range">
        <input type="hidden" name="time_from">
        <input type="hidden" name="time_to">
        <input type="hidden" name="price_from">
        <input type="hidden" name="price_to">
    <?php } ?>
</form>
<script src="/assets/js/filters.js?v4"></script>
<!-- catalog -->