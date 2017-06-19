<?php

namespace Meanbee\OrderStatusAudit\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Ddl\Trigger;
use Magento\Framework\DB\Ddl\TriggerFactory;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /** @var TriggerFactory */
    protected $triggerFactory;

    public function __construct(
        TriggerFactory $triggerFactory
    ) {
        $this->triggerFactory = $triggerFactory;
    }

    /**
     * @inheritdoc
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $connection = $setup->getConnection();

        $setup->startSetup();

        /*
         * Create the sales_order_status_audit table
         */
        $table = $connection
            ->newTable($setup->getTable("sales_order_status_audit"))
            ->addColumn("entity_id", Table::TYPE_INTEGER, null, [
                "identity" => true,
                "unsigned" => true,
                "nullable" => false,
                "primary"  => true,
            ], "Entity ID")
            ->addColumn("parent_id", Table::TYPE_INTEGER, null, [
                "unsigned" => true,
                "nullable" => false,
            ], "Parent ID")
            ->addColumn("status", Table::TYPE_TEXT, 32, [
            ], "Status")
            ->addColumn("created_at", Table::TYPE_TIMESTAMP, null, [
                "nullable" => false,
                "default"  => Table::TIMESTAMP_INIT,
            ], "Created At")
            ->addIndex(
                $setup->getIdxName("sales_order_status_audit", ["parent_id"]),
                ["parent_id"]
            )
            ->addIndex(
                $setup->getIdxName("sales_order_status_audit", ["created_at"]),
                ["created_at"]
            )
            ->addForeignKey(
                $setup->getFkName("sales_order_status_audit", "parent_id", "sales_order", "entity_id"),
                "parent_id",
                $setup->getTable("sales_order"),
                "entity_id",
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            )
            ->setComment("Sales Flat Order Status Audit");
        $connection->createTable($table);

        /*
         * Add triggers to update the table when order status changes
         */
        foreach ([Trigger::EVENT_INSERT, Trigger::EVENT_UPDATE] as $event) {
            $trigger = $this->triggerFactory->create()
                ->setName($connection->getTriggerName("order_status_audit", Trigger::TIME_AFTER, $event))
                ->setTable($setup->getTable("sales_order"))
                ->setTime(Trigger::TIME_AFTER)
                ->setEvent($event);

            $statement = sprintf(
                "INSERT IGNORE INTO %s (%s, %s) VALUES (NEW.%s, NEW.%s);",
                $connection->quoteIdentifier("sales_order_status_audit"),
                $connection->quoteIdentifier("parent_id"),
                $connection->quoteIdentifier("status"),
                $connection->quoteIdentifier("entity_id"),
                $connection->quoteIdentifier("status")
            );

            if ($event == Trigger::EVENT_UPDATE) {
                $statement = sprintf(
                    "IF NEW.%s != OLD.%s THEN %s END IF;",
                    $connection->quoteIdentifier("status"),
                    $connection->quoteIdentifier("status"),
                    $statement
                );
            }

            $trigger->addStatement($statement);

            $connection->dropTrigger($trigger->getName());
            $connection->createTrigger($trigger);
        }

        $setup->endSetup();
    }
}
