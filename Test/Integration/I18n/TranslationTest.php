<?php
declare(strict_types=1);

namespace LokiCheckout\At\Test\Integration\I18n;

use LokiCheckout\At\Test\Integration\ObjectManagerTrait;
use Magento\Framework\App\Area;
use Magento\Framework\TranslateInterface;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class TranslationTest extends TestCase
{
    use ObjectManagerTrait;

    #[DataProvider('getTranslations')]
    public function testAustrianTranslation(string $text, string $expectedTranslation): void
    {
        $translate = $this->create(TranslateInterface::class);
        $translate->setLocale('de_AT');
        $translate->loadData(Area::AREA_FRONTEND, true);

        $translations = $translate->getData();
        $this->assertArrayHasKey($text, $translations, 'No Austrian translation for "'.$text.'"');
        $this->assertSame($expectedTranslation, $translations[$text]);
    }

    /**
     * @return array<int, array{string, string}>
     */
    public static function getTranslations(): array
    {
        return [
            ['Street Name', 'Straße'],
            ['Housenumber', 'Hausnummer'],
            ['Addition', 'Zusatz'],
            ['Postcode', 'Postleitzahl'],
            ['State', 'Bundesland'],
            ['State/Province', 'Bundesland'],
        ];
    }
}
