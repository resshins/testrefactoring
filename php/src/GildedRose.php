<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    CONST AGED_BRIE="Aged Brie";
    CONST BACKSTAGE_TAFKAL80ETC="Backstage passes to a TAFKAL80ETC concert";
    CONST SULFURAS="Sulfuras";    

    /**
     * @var Item[]
     */
    private $items;
    
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Update item attribute for each day that passed 
     */
    public function updateQuality(): void
    {
        // process each item in the list
        foreach ($this->items as $item) {

            // decrease quality 
            if ($item->name != self::AGED_BRIE and $item->name != self::BACKSTAGE_TAFKAL80ETC) {
                if ($item->quality > 0) {
                    if ($item->name != self::SULFURAS) {
                        $item->quality = $item->quality - 1;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;
                    if ($item->name == self::BACKSTAGE_TAFKAL80ETC) {
                        if ($item->sell_in < 11) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                        if ($item->sell_in < 6) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                    }
                }
            }

            // decrease remaining days before expiration 
            if ($item->name != self::SULFURAS) {
                $item->sell_in = $item->sell_in - 1;
            }

            // if expiration date has passed  
            if ($item->sell_in < 0) {
                if ($item->name != self::AGED_BRIE) {
                    if ($item->name != self::BACKSTAGE_TAFKAL80ETC) {
                        if ($item->quality > 0) {
                            if ($item->name != self::SULFURAS) {
                                $item->quality = $item->quality - 1;
                            }
                        }
                    } else {
                        $item->quality = $item->quality - $item->quality;
                    }
                } else {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;
                    }
                }
            }
        }
    }
}
