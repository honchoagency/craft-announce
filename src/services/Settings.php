<?php

namespace honchoagency\craftannounce\services;

use Craft;
use yii\base\Component;
use honchoagency\craftannounce\models\Settings as SettingsModel;

/**
 * Settings service
 */
class Settings extends Component
{
    /**
     * Get the announcement
     *
     * @return SettingsModel
     */
    static public function getSettings(): SettingsModel
    {
        return SettingsModel::getSettings();
    }

    /**
     * Save the settings
     *
     * @return void
     */
    static public function saveSettings(): void
    {
        SettingsModel::saveSettings();
    }
}
