<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    /**
     * Check if the values (days, quality) goes down for regular items
     * @dataProvider getGeneralLowerValueSet
     */
    public function testGeneralLowerValue(Item $item, array $expected): void
    {
        $arraySet = $this->loadAndUpdateItem($item);
        $this->assertSame($expected[0], $arraySet[0]->sell_in);
        $this->assertSame($expected[1], $arraySet[0]->quality);
    }

    /*
        Get set of data to test lowering quality & days left of item
        @param void
        @return array
    */
    public function getGeneralLowerValueSet(): array
    {
        return [
            [
                'item' => new Item('regular', 10, 10),
                'expected' => [9, 9],
            ],
            [
                'item' => new Item('regular', 0, 1),
                'expected' => [-1, 0],
            ],
        ];
    }

    /**
     * Check if the values (days, quality) goes down for conjured items
     * @dataProvider getConjuredLowerValueSet
     */
    public function testConjuredLowerValue(Item $item, array $expected): void
    {
        $arraySet = $this->loadAndUpdateItem($item);
        $this->assertSame($expected[0], $arraySet[0]->sell_in);
        $this->assertSame($expected[1], $arraySet[0]->quality);
    }

    /*
        Get set of data to test lowering quality & days left of conjured items
        @param void
        @return array
    */
    public function getConjuredLowerValueSet(): array
    {
        return [
            [
                'item' => new Item('conjured', 10, 10),
                'expected' => [9, 8],
            ]
        ];
    }


    /**
     * Check if the values (days, quality) goes down even more quicker when expiration date is reached
     * for regular items
     * @dataProvider getGeneralLowerValueExpiredSet
     */
    public function testGeneralLowerValueExpired(Item $item, array $expected): void
    {
        $arraySet = $this->loadAndUpdateItem($item);
        $this->assertSame($expected[0], $arraySet[0]->sell_in);
        $this->assertSame($expected[1], $arraySet[0]->quality);
    }

    /*
        Get set of data to test lowering quality & days left of item
        @return array
    */
    public function getGeneralLowerValueExpiredSet(): array
    {
        return [
            [
                'item' => new Item('regular', 0, 10),
                'expected' => [-1, 8],
            ],
        ];
    }

    /**
     * Check if the quality doesn't go lower than 0
     * for regular items
     * @dataProvider getGeneralNeverNegativeQualitySet
     */
    public function testGeneralNeverNegativeQuality(Item $item, array $expected): void
    {
        $arraySet = $this->loadAndUpdateItem($item);
        $this->assertSame($expected[0], $arraySet[0]->sell_in);
        $this->assertSame($expected[1], $arraySet[0]->quality);
    }

    /*
        Get set of data to test lowering quality & days left of item
        @return array
    */
    public function getGeneralNeverNegativeQualitySet(): array
    {
        return [
            [
                'item' => new Item('regular', 0, 0),
                'expected' => [-1, 0],
            ],
        ];
    }

    /**
     * Test items that increase in quality
     * @dataProvider getEveryDayBiggerValuesSet
     */
    public function testItemBiggerValue(Item $item, array $expected): void
    {
        $arraySet = $this->loadAndUpdateItem($item);
        $this->assertSame($expected[0], $arraySet[0]->sell_in);
        $this->assertSame($expected[1], $arraySet[0]->quality);
    }

    /*
        get set of valid data for getEveryDayBiggerValuesSet
        @return array
    */
    public function getEveryDayBiggerValuesSet(): array
    {
        return [
            [
                'item' => new Item('Aged Brie', 10, 10),
                'expected' => [9, 11],
            ],
            [
                'item' => new Item('Backstage passes to a TAFKAL80ETC concert', 16, 11),
                'expected' => [15, 12],
            ],
        ];
    }

    /**
     * Test backstage items that has specific rules about its quality
     * @dataProvider getBackstageValuesSet
     */
    public function testBackstageValues(Item $item, array $expected): void
    {
        $arraySet = $this->loadAndUpdateItem($item);
        $this->assertSame($expected[0], $arraySet[0]->sell_in);
        $this->assertSame($expected[1], $arraySet[0]->quality);
    }

    /*
        get set of valid data for getBackstageValuesSet
        @return array
    */
    public function getBackstageValuesSet(): array
    {
        return [
            [
                'item' => new Item('Backstage passes to a TAFKAL80ETC concert', 16, 11),
                'expected' => [15, 12],
            ],
            [
                'item' => new Item('Backstage passes to a TAFKAL80ETC concert', 10, 10),
                'expected' => [9, 12],
            ],
            [
                'item' => new Item('Backstage passes to a TAFKAL80ETC concert', 3, 10),
                'expected' => [2, 13],
            ],
            [
                'item' => new Item('Backstage passes to a TAFKAL80ETC concert', 0, 10),
                'expected' => [-1, 0],
            ],
        ];
    }

    /**
     * @dataProvider getImmovableItemSet
     */
    public function testImmovableItem(Item $item, array $expected): void
    {
        $arraySet = $this->loadAndUpdateItem($item);
        $this->assertSame($expected[0], $arraySet[0]->sell_in);
        $this->assertSame($expected[1], $arraySet[0]->quality);
    }

    /*
        get set of valid data for testImmovableItem
        @param void
        @return array
    */
    public function getImmovableItemSet(): array
    {
        return [
            [
                'item' => new Item('Sulfuras', 10, 80),
                'expected' => [10, 80],
            ],
        ];
    }

    private function loadAndUpdateItem(Item $item): array
    {
        $arraySet = [$item];
        $gildedRose = new GildedRose($arraySet);
        $gildedRose->updateQuality();
        return $gildedRose->getItem();
    }
}
