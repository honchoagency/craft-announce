<?php

namespace honchoagency\craftannounce\services;

use yii\base\Component;
use honchoagency\craftannounce\models\Settings as SettingsModel;
use honchoagency\craftannounce\records\Settings as SettingsRecord;
use honchoagency\craftannounce\Plugin;


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

        // get config and merge settings
        $config = Plugin::getInstance()->pluginConfig;

        if ($config) {
            $settings->modalTitle = $config['modalTitle'] ?? $settings->modalTitle;
            $settings->modalEnabled = $config['modalEnabled'] ?? $settings->modalEnabled;
            $settings->bodyText = $config['bodyText'] ?? $settings->bodyText;
            $settings->linkButtonText = $config['linkButtonText'] ?? $settings->linkButtonText;
            $settings->linkButtonUrl = $config['linkButtonUrl'] ?? $settings->linkButtonUrl;
            $settings->buttonText = $config['buttonText'] ?? $settings->buttonText;
            $settings->buttonRedirectUrl = $config['buttonRedirectUrl'] ?? $settings->buttonRedirectUrl;
            $settings->bannerEnabled = $config['bannerEnabled'] ?? $settings->bannerEnabled;
            $settings->bannerText = $config['bannerText'] ?? $settings->bannerText;
            $settings->bannerLinkText = $config['bannerLinkText'] ?? $settings->bannerLinkText;
            $settings->bannerLink = $config['bannerLink'] ?? $settings->bannerLink;
        }

        return $settings;
    }
}
