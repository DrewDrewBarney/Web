<?php

define('CLASS_LIST', 'horList');
define('CLASS_LIST_ITEM', 'horListItem');
define('CLASS_LIST_ITEM_LINK', 'horListItemLink');
define('CLASS_SELECTED_LIST_ITEM_LINK', 'horListSelectedItemLink');

function makeMenu(array $menu, string $selectedCaption = '?', string $method = 'get') {
    $list = Tag::make('ul', '', ['class' => CLASS_LIST]);

    foreach ($menu as $caption => $link) {

        $selected = $link === $selectedCaption;

        $li = $list->makeChild('li', '', ['class' => CLASS_LIST_ITEM]);
        $classes = $selected ? CLASS_LIST_ITEM_LINK . ' ' . CLASS_SELECTED_LIST_ITEM_LINK : CLASS_LIST_ITEM_LINK;

        $li->makeChild('a', $caption, ['href' => $link, 'class' => $classes]);
    }
    return $list;
}

