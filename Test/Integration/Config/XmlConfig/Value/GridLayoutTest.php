<?php
declare(strict_types=1);

namespace LokiCheckout\At\Test\Integration\Config\XmlConfig\Value;

use LokiCheckout\At\Test\Integration\ObjectManagerTrait;
use LokiCheckout\Core\Config\XmlConfig;
use PHPUnit\Framework\TestCase;

class GridLayoutTest extends TestCase
{
    use ObjectManagerTrait;

    public function testGetGridByCountryId(): void
    {
        $xmlConfig = $this->get(XmlConfig::class);
        $grid = $xmlConfig->getGridLayout('default_grid')->getGridByCountryId('AT');

        $this->assertSame('austria', $grid->getName());
        $this->assertSame('default', $grid->getParent());
    }
}
