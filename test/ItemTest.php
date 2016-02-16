<?php

require_once 'ItemBuilder.php';

class ItemTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    function should_set_name_and_values()
    {
        $itemBuilder = ItemBuilder::newItem();

        $item = $itemBuilder->agedBrie()->withSellIn(3)->ofQuality(7);

        $this->assertEquals("Aged Brie", $item->name);
        $this->assertEquals(3, $item->sellIn);
        $this->assertEquals(7, $item->quality);

    }

}
