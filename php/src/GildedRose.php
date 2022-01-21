<?php

declare(strict_types=1);

namespace GildedRose;

use phpDocumentor\Reflection\Types\Boolean;

final class GildedRose
{
    public const AGED_BRIE = 'Aged Brie';

    public const BACKSTAGE_TAFKAL80ETC = 'Backstage passes to a TAFKAL80ETC concert';

    public const SULFURAS = 'Sulfuras';

    /**
     * @var Item[]
     */
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItem(): array
    {
        return $this->items;
    }

    /**
     * Update item attribute for each day that passed
     */
    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            // BR-1 : decrease remaining days before expiration, except a specific group
            if (! in_array($item->name, self::getListAgeless(), true)) {
                $item->sell_in = $item->sell_in - 1;
            }

            // BR-1 : decrease quality, except items from specific groups
            if (! in_array($item->name, $this->getListIncQualityInsteadDec(), true) &&
                ! in_array($item->name, $this->getListImmovableQuality(), true)
            ) {
                // BR-3 : Quality never negative
                if ($item->quality > 0) {
                    // BR-2 : Quality degrades twice as fast if expired
                    // BR-9 : Conjured items degrades twice as fast
                    if ($item->sell_in < 0 || self::isConjuredItem($item)) {
                        $item->quality = $item->quality - 2;
                    } else {
                        $item->quality = $item->quality - 1;
                    }
                }

                // BR-4 | BR-7: increase quality, if the item is part of a specific group
            } elseif (in_array($item->name, $this->getListIncQualityInsteadDec(), true)) {
                // BR-5 : Quality never above 50
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;
                    // BR-7 : for backstage, increase even more the quality the closer the expiration date
                    if ($item->name === self::BACKSTAGE_TAFKAL80ETC) {
                        // if expiration date of a backstage is 10 days or lower, the quality goes up by 2
                        if ($item->sell_in < 11 && $item->sell_in > 5) {
                            $item->quality = $item->quality + 1;
                        // if the expiration date of a backstage is 5 days or lower, the quality goes up by 3
                        } elseif ($item->sell_in < 6 && $item->sell_in > -1) {
                            $item->quality = $item->quality + 2;
                        } elseif ($item->sell_in < 0) {
                            $item->quality = 0;
                        }
                    }
                }
            }

            // BR-5 : Quality never above 50
            if ($item->quality > 50 && ! in_array($item->name, $this->getListImmovableQuality(), true)) {
                $item->quality = 50;
            // BR-3 : Quality never negative
            } elseif ($item->quality < 0) {
                $item->quality = 0;
            }
        }
    }


    /**
     * Group of items that increase in quality as the time passes,
     * instead of decreasing in quality
     */
    private static function isConjuredItem(Item $item): bool 
    {        
        return (stripos($item->name, 'conjured') !== false);
    }

    /**
     * Group of items that increase in quality as the time passes,
     * instead of decreasing in quality
     */
    private static function getListIncQualityInsteadDec(): array
    {
        return [self::AGED_BRIE, self::BACKSTAGE_TAFKAL80ETC];
    }

    /**
     * Group of items that doesn't age
     */
    private static function getListAgeless(): array
    {
        return [self::SULFURAS];
    }

    /**
     * Group of items that doesn't decrease in quality
     */
    private static function getListImmovableQuality(): array
    {
        return [self::SULFURAS];
    }
}
