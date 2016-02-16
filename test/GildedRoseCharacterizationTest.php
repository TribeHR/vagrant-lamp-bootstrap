<?php

require_once 'GildedRose.php';
require_once 'ItemBuilder.php';

class GildedRoseCharacterizationTest extends PHPUnit_Framework_TestCase
{

    public function test_items_should_degrade_in_quality()
    {
        $initialQuality = 5;
        $item = ItemBuilder::newItem()
            ->withQuality($initialQuality)
            ->build();

        $this->updateQualityForItem($item);

        $this->assertEquals($initialQuality-1, $item->quality);
    }

    public function test_expired_items_should_decrease_quality_twice_as_fast()
    {
        $initialQuality = 13;
        $item = ItemBuilder::newItem()
            ->withQuality($initialQuality)
            ->withSellIn(-1)
            ->build();

        $this->updateQualityForItem($item);

        $this->assertEquals($initialQuality-2, $item->quality);
    }

    public function test_quality_should_never_go_below_zero()
    {
        $initialQuality = 0;
        $item = ItemBuilder::newItem()
            ->withQuality($initialQuality)
            ->withSellIn(10)
            ->build();

        $this->updateQualityForItem($item);

        $this->assertEquals(0, $item->quality);
    }

    public function test_just_expired_items_should_decrease_quality_twice_as_fast()
    {
        $initialQuality = 13;
        $item = ItemBuilder::newItem()
            ->withQuality($initialQuality)
            ->withSellIn(0)
            ->build();

        $this->updateQualityForItem($item);

        $this->assertEquals($initialQuality-2, $item->quality);
    }

    public  function test_aged_brie_should_increase_in_quality()
    {
        $agedBrie = ItemBuilder::newItem()
            ->withName("Aged Brie")
            ->withQuality(5)
            ->build();

        $this->updateQualityForItem($agedBrie);

        $this->assertEquals(6, $agedBrie->quality);
    }

    public  function test_quality_should_never_go_above_50()
    {
        $agedBrie = ItemBuilder::newItem()
            ->withName("Aged Brie")
            ->withQuality(50)
            ->build();

        $this->updateQualityForItem($agedBrie);

        $this->assertEquals(50, $agedBrie->quality);
    }

    public function test_items_should_decrease_sell_in()
    {
        $initialSellIn = 5;
        $item = ItemBuilder::newItem()
            ->withSellIn($initialSellIn)
            ->build();

        $this->updateQualityForItem($item);

        $this->assertEquals($initialSellIn-1, $item->sellIn);
    }

    public function test_sulfuras_should_not_change_quality_or_sell_in()
    {
        $sulfuras = ItemBuilder::newItem()
            ->withName("Sulfuras, Hand of Ragnaros")
            ->withSellIn(10)
            ->withQuality(20)
            ->build();

        $this->updateQualityForItem($sulfuras);

        $this->assertEquals(10, $sulfuras->sellIn);
        $this->assertEquals(20, $sulfuras->quality);
    }

    public static function backstage_rules()
    {
        return array(
            "incr. 1 when sellIn > 10" => array(11, 10, 11),
            "incr. 2 when 5 < sellIn <= 10 (max)" => array(10, 10, 12),
            "incr. 2 when 5 < sellIn <= 10 (min)" => array(6, 10, 12),
            "incr. 3 when 0 < sellIn <= 5 (max)" => array(5, 10, 13),
            "incr. 3 when 0 < sellIn <= 5 (min)" => array(1, 10, 13),
            "stays at 0 when sellIn <= 0 (max)" => array(0, 10, 0),
            "stays at 0 when sellIn <= 0 (...)" => array(-1, 10, 0)
        );
    }

    /**
     * @dataProvider backstage_rules
     */
    public function test_backstage_passes_quality(
        $sellIn,
        $quality,
        $expected
    )
    {
        $pass = ItemBuilder::newItem()
            ->withName("Backstage passes to a TAFKAL80ETC concert")
            ->withSellIn($sellIn)
            ->withQuality($quality)
            ->build();

        $this->updateQualityForItem($pass);

        $this->assertEquals($expected, $pass->quality);
    }

    /**
     * @param $item
     */
    private function updateQualityForItem($item)
    {
        $rose = new GildedRose(array($item));
        $rose->updateQuality();
    }

}

?>