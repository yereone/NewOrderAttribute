<?php
namespace Yereone\NewOrderAttribute\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Model\Order;

class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Sales\Setup\SalesSetupFactory
     */
    protected $salesSetupFactory;

    /**
     * @param \Magento\Sales\Setup\SalesSetupFactory $salesSetupFactory
     */
    public function __construct(
        \Magento\Sales\Setup\SalesSetupFactory $salesSetupFactory
    ) {
        $this->salesSetupFactory = $salesSetupFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) 
    {
        $installer = $setup;

        $installer->startSetup();

        $salesSetup = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $installer]);

        $salesSetup->addAttribute(Order::ENTITY, 'is_important', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length'=> 255,
            'visible' => false,
            'nullable' => true
        ]);

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'is_important',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' =>'Is Important'
            ]
        );

        $installer->endSetup();
    }
}
