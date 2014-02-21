<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\ConfigurableProduct\Model\Product\CartConfiguration\Plugin;

class ConfigurableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\ConfigurableProduct\Model\Product\CartConfiguration\Plugin\Configurable
     */
    protected $model;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $invocationChainMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    protected function setUp()
    {
        $this->invocationChainMock = $this->getMock('Magento\Code\Plugin\InvocationChain', array(), array(), '', false);
        $this->productMock = $this->getMock('Magento\Catalog\Model\Product', array(), array(), '', false);
        $this->model = new Configurable();
    }

    public function testAroundIsProductConfiguredChecksThatSuperAttributeIsSetWhenProductIsConfigurable()
    {
        $config = array('super_attribute' => 'valid_value');
        $this->productMock->expects($this->once())
            ->method('getTypeId')
            ->will($this->returnValue(\Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE));
        $this->invocationChainMock->expects($this->never())->method('proceed');
        $this->assertEquals(
            true,
            $this->model->aroundIsProductConfigured(array($this->productMock, $config), $this->invocationChainMock)
        );
    }

    public function testAroundIsProductConfiguredProceedsChainInvocationWhenProductIsNotConfigurable()
    {
        $config = array('super_group' => 'valid_value');
        $this->productMock->expects($this->once())
            ->method('getTypeId')
            ->will($this->returnValue('custom_product_type'));
        $this->invocationChainMock->expects($this->once())
            ->method('proceed')
            ->with(array($this->productMock, $config));
        $this->model->aroundIsProductConfigured(array($this->productMock, $config), $this->invocationChainMock);
    }
}
