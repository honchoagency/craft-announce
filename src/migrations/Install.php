<?php

namespace honchoagency\craftannounce\migrations;

use Craft;
use craft\db\Migration;

/**
 * Install migration.
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $createSettingsTable = $this->createTable(
            '{{%announce_settings}}',
            [
                'handle' => $this->string()->notNull(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                'PRIMARY KEY([[handle]])',
                // Custom columns in the table
                'modalTitle' => $this->text()->notNull(),
                'bodyText' => $this->text()->notNull(),
                'linkButtonText' => $this->text()->notNull(),
                'linkButtonUrl' => $this->text()->notNull(),
                'modalEnabled' => $this->boolean()->notNull()->defaultValue(true),
                'buttonText' => $this->text()->notNull(),
                'buttonRedirectUrl' => $this->text()->notNull(),
                'bannerEnabled' => $this->boolean()->notNull()->defaultValue(true),
                'bannerText' => $this->text()->notNull(),
                'bannerLinkText' => $this->text()->notNull(),
                'bannerLink' => $this->text()->notNull(),
            ]
        );

        if ($createSettingsTable) {
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropTableIfExists('{{%announce_settings}}');

        return true;
    }
}
