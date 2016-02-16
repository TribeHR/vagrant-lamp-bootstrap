<?php

require_once 'GildedRose.php';
require_once 'ItemBuilder.php';

class GildedRoseApprovalTest extends PHPUnit_Framework_TestCase
{
    const GILDED_ROSE_RECEIVED_FILE = "GildedRose.received.txt";

    const GILDED_ROSE_APPROVED_FILE = "GildedRose.approved.txt";

    public function test()
    {
        $received = "";

        $items = $this->makeItems();

        $sut = new GildedRose($items);

        for ($day = 1; $day <= 100; $day++) {
            $received .= $this->formatItemsForDay($day, $items);
            $sut->updateQuality();
        }

        $receivedExpected = $this->compareToApproved($received);

        if (!$receivedExpected) {
            $this->writeReceivedFile($received);
        }

        $this->assertTrue($receivedExpected,
            "Did not match approved results. Compare files '" .
            self::GILDED_ROSE_APPROVED_FILE . "' and '" .
            self::GILDED_ROSE_RECEIVED_FILE . "'");
    }

    private function makeItems()
    {
        $items = [];

        $possibleItems = [
            "+5 Dexterity Vest",
            "Aged Brie",
            "Backstage passes to a TAFKAL80ETC concert",
            "Elixir of the Mongoose",
            "arbitrary item"
        ];

        for ($qualityIndex = 0; $qualityIndex <= 50; $qualityIndex += 25) {
            foreach ($possibleItems as $possibleItem) {
                $item = new Item($possibleItem, 20, $qualityIndex);
                array_push($items, $item);
            }
        }

        // Sulfurus does not need different qualities or sellIns, as they never change
        array_push($items, new Item("Sulfuras, Hand of Ragnaros", 0, 80));

        return $items;
    }

    private function formatItemsForDay($day, $items)
    {
        $results = "Day " . $day . "\n";
        $format = "%-42s  Quality: %2d   SellIn: %3d\n";

        foreach ($items as $item) {
            $results .= sprintf($format,
                $item->name, $item->quality, $item->sellIn);
        }

        return $results . "\n";
    }

    private function compareToApproved($received)
    {
        $expected = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . self::GILDED_ROSE_APPROVED_FILE);

        $receivedExpected = (strcmp($expected, $received) == 0);
        return $receivedExpected;
    }

    private function writeReceivedFile($received)
    {
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . self::GILDED_ROSE_RECEIVED_FILE, $received);
    }

}


