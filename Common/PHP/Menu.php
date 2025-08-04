<?php
declare(strict_types=1);

class Menu {

    const MENU_CLASS = 'menu';
    const MENU_ITEM_CLASS = 'menuItem';
    const MENU_SELECTED_ITEM_CLASS = 'menuSelectedItem';
    const MENU_LINK_CLASS = 'menuItemLink';

    static ?Tag $div = null;

    private static function itemSelected(array $item, string $currentUrl): bool {
        if ($item['class'] === $currentUrl) {
            return true;
        }

        if (!empty($item['submenu'])) {
            foreach ($item['submenu'] as $subItem) {
                if (self::itemSelected($subItem, $currentUrl)) {
                    return true;
                }
            }
        }

        return false;
    }

    private static function buildMenu(array $items, string $currentUrl): void {
        $result = Tag::make('menu', '', ['class' => self::MENU_CLASS]);

        foreach ($items as $item) {
            $isSelected = self::itemSelected($item, $currentUrl);
            $class = self::MENU_ITEM_CLASS . ($isSelected ? ' ' . self::MENU_SELECTED_ITEM_CLASS : '');
            $caption = $item['caption'];
            $url = '?page=' . $item['class'];

            $result
                ->makeChild('li', '', ['class' => $class])
                ->makeChild('a', $caption, ['class' => self::MENU_LINK_CLASS, 'href' => $url]);
        }

        self::$div->addChild($result);

        // Submenus rendered separately, just like in your original
        self::buildSubMenus($items, $currentUrl);
    }

    private static function buildSubMenus(array $items, string $currentUrl): void {
        foreach ($items as $item) {
            if (!empty($item['submenu']) && self::itemSelected($item, $currentUrl)) {
                self::buildMenu($item['submenu'], $currentUrl);
            }
        }
    }

    static function make(array $items): Tag {
        self::$div = Tag::make('div', '', ['class' => 'menuTitle']);
        self::$div->makeChild('header', Context::siteTitle());

        $currentUrl = Tools::gp('page') ?? '';
        self::buildMenu($items, $currentUrl);

        return self::$div;
    }
}
