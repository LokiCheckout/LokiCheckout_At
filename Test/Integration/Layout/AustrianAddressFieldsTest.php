<?php
declare(strict_types=1);

namespace LokiCheckout\At\Test\Integration\Layout;

use Loki\Components\Util\Controller\LayoutLoader;
use LokiCheckout\At\Test\Integration\ObjectManagerTrait;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use PHPUnit\Framework\TestCase;

#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[AppArea('frontend')]
class AustrianAddressFieldsTest extends TestCase
{
    use ObjectManagerTrait;

    #[ConfigFixture('loki_checkout/general/theme', 'default', 'store', 'default')]
    public function testAustrianFieldsWithDefaultSkin(): void
    {
        $layout = $this->getLayout('AT', 'AT');
        foreach (['shipping', 'billing'] as $addressType) {
            $this->assertAustrianFields($layout, $addressType);
            $this->assertHouseNumberAndAdditionSideBySide($layout, $addressType);
        }
    }

    #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    public function testAustrianFieldsWithOnestepSkin(): void
    {
        $layout = $this->getLayout('AT', 'AT');
        foreach (['shipping', 'billing'] as $addressType) {
            $this->assertAustrianFields($layout, $addressType);
            $this->assertHouseNumberAndAdditionNotSideBySide($layout, $addressType);
        }
    }

    #[ConfigFixture('loki_checkout/general/theme', 'default', 'store', 'default')]
    public function testMixedCountriesWithAustrianBillingAddress(): void
    {
        $layout = $this->getLayout('DE', 'AT');
        $this->assertAustrianFields($layout, 'billing');
        $this->assertHouseNumberAndAdditionSideBySide($layout, 'billing');
        $this->assertNotAustrianFields($layout, 'shipping');
        $this->assertHouseNumberAndAdditionNotSideBySide($layout, 'shipping');
        $this->assertGridNames($layout, 'austria', 'billing', 'shipping');
    }

    #[ConfigFixture('loki_checkout/general/theme', 'default', 'store', 'default')]
    public function testMixedCountriesWithAustrianShippingAddress(): void
    {
        $layout = $this->getLayout('AT', 'DE');
        $this->assertAustrianFields($layout, 'shipping');
        $this->assertHouseNumberAndAdditionSideBySide($layout, 'shipping');
        $this->assertNotAustrianFields($layout, 'billing');
        $this->assertHouseNumberAndAdditionNotSideBySide($layout, 'billing');
        $this->assertGridNames($layout, 'austria', 'shipping', 'billing');
    }

    private function assertAustrianFields(LayoutInterface $layout, string $addressType): void
    {
        foreach ($this->getExpectedLabels() as $field => $expectedLabel) {
            $block = $this->getBlock($layout, $addressType, $field);
            $this->assertEquals($expectedLabel, (string)$block->getFieldLabel(), $block->getNameInLayout());
        }

        $postcode = $this->getBlock($layout, $addressType, 'postcode');
        $this->assertEquals('1234', $postcode->getPlaceholder());
        $this->assertEquals('9999', $postcode->getFieldAttributes()['x-mask'] ?? null);
    }

    private function assertNotAustrianFields(LayoutInterface $layout, string $addressType): void
    {
        foreach (['street1' => 'Housenumber', 'street2' => 'Addition', 'region' => 'State'] as $field => $label) {
            $block = $this->getBlock($layout, $addressType, $field);
            $this->assertNotEquals($label, (string)$block->getFieldLabel(), $block->getNameInLayout());
        }

        $postcode = $this->getBlock($layout, $addressType, 'postcode');
        $this->assertNotEquals('1234', (string)$postcode->getPlaceholder());
        $this->assertNotEquals('9999', $postcode->getFieldAttributes()['x-mask'] ?? null);
    }

    private function assertHouseNumberAndAdditionSideBySide(LayoutInterface $layout, string $addressType): void
    {
        foreach (['street1', 'street2'] as $field) {
            $block = $this->getBlock($layout, $addressType, $field);
            $this->assertEquals(6, (int)$block->getColSpan(), $block->getNameInLayout().' col_span');
            $this->assertEquals(3, (int)$block->getMdColSpan(), $block->getNameInLayout().' md_col_span');
        }
    }

    private function assertHouseNumberAndAdditionNotSideBySide(LayoutInterface $layout, string $addressType): void
    {
        foreach (['street1', 'street2'] as $field) {
            $block = $this->getBlock($layout, $addressType, $field);
            $this->assertNotEquals(3, (int)$block->getMdColSpan(), $block->getNameInLayout().' md_col_span');
        }
    }

    private function assertGridNames(
        LayoutInterface $layout,
        string $gridName,
        string $matchingAddressType,
        string $otherAddressType
    ): void {
        $config = $this->get(Config::class);
        $matchingForm = $layout->getBlock('loki-checkout.'.$matchingAddressType.'.address.form');
        $otherForm = $layout->getBlock('loki-checkout.'.$otherAddressType.'.address.form');
        $this->assertInstanceOf(AbstractBlock::class, $matchingForm);
        $this->assertInstanceOf(AbstractBlock::class, $otherForm);
        $this->assertEquals($gridName, $config->getGrid($matchingForm)->getName());
        $this->assertNotEquals($gridName, $config->getGrid($otherForm)->getName());
    }

    /**
     * @return array<string, string>
     */
    private function getExpectedLabels(): array
    {
        return [
            'street0' => 'Street Name',
            'street1' => 'Housenumber',
            'street2' => 'Addition',
            'postcode' => 'Postcode',
            'region' => 'State',
        ];
    }

    private function getBlock(LayoutInterface $layout, string $addressType, string $field): AbstractBlock
    {
        $blockName = 'loki-checkout.'.$addressType.'.address.'.$field;
        $block = $layout->getBlock($blockName);
        $this->assertInstanceOf(AbstractBlock::class, $block, $blockName);

        return $block;
    }

    private function getLayout(string $shippingCountryId, string $billingCountryId): LayoutInterface
    {
        $fixture = $this->get(DataFixtureStorageManager::class)->getStorage()->get('cart');
        $this->assertInstanceOf(DataObject::class, $fixture);

        $cart = $this->get(CartRepositoryInterface::class)->get((int)$fixture->getId());
        $this->assertInstanceOf(Quote::class, $cart);
        $cart->getShippingAddress()->setCountryId($shippingCountryId);
        $cart->getBillingAddress()->setCountryId($billingCountryId);

        $checkoutState = $this->get(CheckoutState::class);
        $checkoutState->setQuote($cart);
        $quoteId = $cart->getId();
        $this->assertIsNumeric($quoteId);
        $checkoutState->setQuoteId((int)$quoteId);
        $this->get(CheckoutSession::class)->replaceQuote($cart);

        return $this->get(LayoutLoader::class)->load(['checkout_index_index'], [], true);
    }
}
