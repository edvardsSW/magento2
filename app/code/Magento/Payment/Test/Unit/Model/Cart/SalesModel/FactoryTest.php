<?php declare(strict_types=1);
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Payment\Test\Unit\Model\Cart\SalesModel;

use Magento\Framework\ObjectManagerInterface;
use Magento\Payment\Model\Cart\SalesModel\Factory;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /** @var Factory */
    protected $_model;

    /** @var ObjectManagerInterface|MockObject */
    protected $_objectManagerMock;

    protected function setUp(): void
    {
        $this->_objectManagerMock = $this->createMock(ObjectManagerInterface::class);
        $this->_model = new Factory($this->_objectManagerMock);
    }

    /**
     * @param string $salesModelClass
     * @param string $expectedType
     * @dataProvider createDataProvider
     */
    public function testCreate($salesModelClass, $expectedType)
    {
        $salesModel = $this->createPartialMock($salesModelClass, ['__wakeup']);
        $this->_objectManagerMock->expects(
            $this->once()
        )->method(
            'create'
        )->with(
            $expectedType,
            ['salesModel' => $salesModel]
        )->will(
            $this->returnValue('some value')
        );
        $this->assertEquals('some value', $this->_model->create($salesModel));
    }

    /**
     * @return array
     */
    public function createDataProvider()
    {
        return [
            [Quote::class, \Magento\Payment\Model\Cart\SalesModel\Quote::class],
            [Order::class, \Magento\Payment\Model\Cart\SalesModel\Order::class]
        ];
    }

    public function testCreateInvalid()
    {
        $this->expectException('InvalidArgumentException');
        $this->_model->create('any invalid');
    }
}
