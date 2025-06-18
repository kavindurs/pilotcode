<?php

if (!function_exists('getCategoryIcon')) {
    function getCategoryIcon($categoryName) {
        return match ($categoryName) {
            'Animals & Pets' => 'fa-paw',
            'Events & Entertainment' => 'fa-ticket',
            'Home & Garden' => 'fa-home',
            'Restaurants & Bars' => 'fa-utensils',
            'Beauty & Well-being' => 'fa-spa',
            'Food, Beverages & Tobacco' => 'fa-wine-glass',
            'Home Services' => 'fa-tools',
            'Shopping & Fashion' => 'fa-shopping-bag',
            'Business Services' => 'fa-briefcase',
            'Legal Services & Government' => 'fa-balance-scale',
            'Sports' => 'fa-basketball-ball',
            'Construction & Manufacturing' => 'fa-hard-hat',
            'Health & Medical' => 'fa-heartbeat',
            'Media & Publishing' => 'fa-newspaper',
            'Travel & Vacation' => 'fa-plane',
            'Education & Training' => 'fa-graduation-cap',
            'Hobbies & Crafts' => 'fa-palette',
            'Money & Insurance' => 'fa-dollar-sign',
            'Electronics & Technology' => 'fa-laptop',
            'Public & Local Services' => 'fa-city',
            'Utilities' => 'fa-bolt',
            'Vehicles & Transportation' => 'fa-car',
            default => 'fa-folder',
        };
    }
}
