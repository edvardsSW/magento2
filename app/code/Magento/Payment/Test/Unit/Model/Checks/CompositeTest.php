<?php declare(strict_types=1);
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Payment\Test\Unit\Model\Checks;

use Magento\Payment\Model\Checks\Composite;
use Magento\Payment\Model\Checks\SpecificationInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Model\Quote;
use PHPUnit\Framework\TestCase;

class CompositeTest extends TestCase
{
    /**
     * @dataProvider paymentMethodDataProvider
     * @param bool $expectation
     */
    public function testIsApplicable($expectation)
    {
        $quote = $this->getMockBuilder(Quote::class)->disableOriginalConstructor()->setMethods(
            []
        )->getMock();
        $paymentMethod = $this->getMockBuilder(
            MethodInterface::class
        )->disableOriginalConstructor()->setMethods([])->getMock();

        $specification = $this->getMockBuilder(
            SpecificationInterface::class
        )->disableOriginalConstructor()->setMethods([])->getMock();
        $specification->expects($this->once())->method('isApplicable')->with($paymentMethod, $quote)->will(
            $this->returnValue($expectation)
        );
        $model = new Composite([$specification]);
        $this->assertEquals($expectation, $model->isApplicable($paymentMethod, $quote));
    }

    /**
     * @return array
     */
    public function paymentMethodDataProvider()
    {
        return [[true], [false]];
    }
}
