<?php

//require_once 'Item.php';


define("FRESH", 5);
define("NO_QUALITY", 0);
define("NAX_QUALITY", 50);

class ItemBuilder
{

    private $name;
    private $sellIn;
    private $quality;

    function __construct()
    {
        $this->name = "An Ordinary Item";
        $this->sellIn = 10;
        $this->quality = 10;
    }

    public static function newItem()
    {
        return new ItemBuilder();
    }

    function ordinaryItem()
    {
        return $this->withName("any ordinary item");
    }

    public function withName($name)
    {
        $this->name = $name;
        return $this;
    }

    function agedBrie()
    {
        return $this->withName("Aged Brie");
    }

    function sulfuras()
    {
        return $this->withName("Sulfuras, Hand of Ragnaros");
    }

    function backstagePass()
    {
        return $this->withName("Backstage passes to a TAFKAL80ETC concert");
    }

    function conjuredItem()
    {
        return $this->withName("Conjured Mana Cake");
    }

    function almostExpired()
    {
        return $this->withSellIn(1);
    }

    function justExpired()
    {
        return $this->withSellIn(0);
    }

    function expired()
    {
        return $this->withSellIn(-3);
    }

    function toSellIn($days)
    {
        return $this->withSellIn($days)->item();
    }

    function withSellIn($days)
    {
        $this->sellIn = $days;
        return $this;
    }

    function ofQuality($number)
    {
        return $this->withQuality($number)->item();
    }

    function ofNoQuality()
    {
        return $this->withQuality(NO_QUALITY)->item();
    }

    function ofMaxQuality()
    {
        return $this->withQuality(NAX_QUALITY)->item();
    }

    function withQuality($number)
    {
        $this->quality = $number;
        return $this;
    }

    public function build()
    {
        return new Item(
            $this->name,
            $this->sellIn,
            $this->quality
        );
    }

    function item()
    {
        return $this->build();
    }

}

?>
