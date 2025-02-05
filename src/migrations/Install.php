<?php

namespace honcho\craftannounce\migrations;

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
            '{{%honcho_announce_settings}}',
            [
                'handle' => $this->string()->notNull(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                'PRIMARY KEY([[handle]])',
                // Custom columns in the table
                'announcement' => $this->text()->notNull(),
                'bodyText' => $this->text()->notNull(),
                'linkText' => $this->text()->notNull(),
                'link' => $this->text()->notNull(),
                'enabled' => $this->boolean()->notNull()->defaultValue(true),
                'adminDisabled' => $this->boolean()->notNull()->defaultValue(false),
                'continueButtonText' => $this->text()->notNull(),
                'continueButtonURL' => $this->text()->notNull(),
                'alertEnabled' => $this->boolean()->notNull()->defaultValue(true),
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
        $this->dropTableIfExists('{{%honcho_announce_settings}}');

        return true;
    }
}
