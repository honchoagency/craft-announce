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
            '{{%honcho_announce_settings}}',
            [
                'handle' => $this->string()->notNull(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                'PRIMARY KEY([[handle]])',
                // Custom columns in the table
                'loginModalTitle' => $this->text()->notNull(),
                'loginModalBodyText' => $this->text()->notNull(),
                'loginModalLinkText' => $this->text()->notNull(),
                'loginModalLink' => $this->text()->notNull(),
                'loginModalEnabled' => $this->boolean()->notNull()->defaultValue(true),
                'loginModalAdminEnabled' => $this->boolean()->notNull()->defaultValue(false),
                'loginModalContinueButtonText' => $this->text()->notNull(),
                'loginModalContinueButtonURL' => $this->text()->notNull(),
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
        $this->dropTableIfExists('{{%honcho_announce_settings}}');

        return true;
    }
}
