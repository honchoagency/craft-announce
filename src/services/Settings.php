<?php

namespace honchoagency\craftannounce\services;

use Craft;
use yii\base\Component;
use honchoagency\craftannounce\models\Settings as SettingsModel;
use honchoagency\craftannounce\records\Settings as SettingsRecord;


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
    public function getSettings(): SettingsModel
    {
        $settings = new SettingsModel();
        $settingsRecord = SettingsRecord::findOne(['handle' => 'settings']);

        if ($settingsRecord) {
            $settings->modalTitle = $settingsRecord->modalTitle;
            $settings->modalEnabled = $settingsRecord->modalEnabled;
            $settings->bodyText = $settingsRecord->bodyText;
            $settings->linkButtonText = $settingsRecord->linkButtonText;
            $settings->linkButtonUrl = $settingsRecord->linkButtonUrl;
            $settings->buttonText = $settingsRecord->buttonText;
            $settings->buttonRedirectUrl = $settingsRecord->buttonRedirectUrl;
            $settings->bannerEnabled = $settingsRecord->bannerEnabled;
            $settings->bannerText = $settingsRecord->bannerText;
            $settings->bannerLinkText = $settingsRecord->bannerLinkText;
            $settings->bannerLink = $settingsRecord->bannerLink;
        }

        return $settings;
    }
}
