<?php

namespace honchoagency\craftannounce\models;

use Craft;
use craft\base\Model;
use craft\validators\UrlValidator;
use honchoagency\craftannounce\Plugin;
use honchoagency\craftannounce\records\Settings as SettingsRecord;

/**
 * Settings model
 */
class Settings extends Model
{
    // Public Properties
    public string $loginModalTitle = '';
    public bool $loginModalEnabled = false;
    public bool $loginModalAdminEnabled = true;
    public string $loginModalBodyText = '';
    public string $loginModalLinkText = '';
    public string $loginModalLink = '';
    public string $loginModalContinueButtonText = '';
    public string $loginModalContinueButtonURL = '';
    public bool $bannerEnabled = false;
    public string $bannerText = '';
    public string $bannerLinkText = '';
    public string $bannerLink = '';
    public bool $bannerLinkOpenInNewTab = true;

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            [['loginModalTitle', 'bannerText'], 'required'],
            [['loginModalLink','bannerLink','loginModalContinueButtonURL'], UrlValidator::class],
        ]);
    }

    /**
     * Get the settings
     *
     * @return Settings
     */
    static public function getSettings(): Settings
    {
        $settings = new Settings();
        $settingsRecord = SettingsRecord::findOne(['handle' => 'settings']);

        if ($settingsRecord) {
            $settings->loginModalTitle = $settingsRecord->loginModalTitle;
            $settings->loginModalEnabled = $settingsRecord->loginModalEnabled;
            $settings->loginModalAdminEnabled = $settingsRecord->loginModalAdminEnabled;
            $settings->loginModalBodyText = $settingsRecord->loginModalBodyText;
            $settings->loginModalLinkText = $settingsRecord->loginModalLinkText;
            $settings->loginModalLink = $settingsRecord->loginModalLink;
            $settings->loginModalContinueButtonText = $settingsRecord->loginModalContinueButtonText;
            $settings->loginModalContinueButtonURL = $settingsRecord->loginModalContinueButtonURL;
            $settings->bannerEnabled = $settingsRecord->bannerEnabled;
            $settings->bannerText = $settingsRecord->bannerText;
            $settings->bannerLinkText = $settingsRecord->bannerLinkText;
            $settings->bannerLink = $settingsRecord->bannerLink;
        }

        return $settings;
    }

    /**
     * Save the settings
     *
     * @return void
     */
    static public function saveSettings(): void
    {
        $settings = new Settings();
        $requestParams = Craft::$app->getRequest()->getBodyParams();

        $settings->loginModalTitle = $requestParams['loginModalTitle'];
        $settings->loginModalEnabled = $requestParams['loginModalEnabled'];
        $settings->loginModalAdminEnabled = $requestParams['loginModalAdminEnabled'];
        $settings->loginModalBodyText = $requestParams['loginModalBodyText'];
        $settings->loginModalLinkText = $requestParams['loginModalLinkText'];
        $settings->loginModalLink = $requestParams['loginModalLink'];
        $settings->loginModalContinueButtonText = $requestParams['loginModalContinueButtonText'];
        $settings->loginModalContinueButtonURL = $requestParams['loginModalContinueButtonURL'];
        $settings->bannerEnabled = $requestParams['bannerEnabled'];
        $settings->bannerText = $requestParams['bannerText'];
        $settings->bannerLinkText = $requestParams['bannerLinkText'];
        $settings->bannerLink = $requestParams['bannerLink'];

        if ($settings->validate()) {
            $settingsRecord = SettingsRecord::findOne(['handle' => 'settings']);

            if (!$settingsRecord) {
                $settingsRecord = new SettingsRecord();
                $settingsRecord->handle = 'settings';
            }

            $settingsRecord->loginModalTitle = $settings->loginModalTitle;
            $settingsRecord->loginModalEnabled = $settings->loginModalEnabled;
            $settingsRecord->loginModalAdminEnabled = $settings->loginModalAdminEnabled;
            $settingsRecord->loginModalBodyText = $settings->loginModalBodyText;
            $settingsRecord->loginModalLinkText = $settings->loginModalLinkText;
            $settingsRecord->loginModalLink = $settings->loginModalLink;
            $settingsRecord->loginModalContinueButtonText = $settings->loginModalContinueButtonText;
            $settingsRecord->loginModalContinueButtonURL = $settings->loginModalContinueButtonURL;
            $settingsRecord->bannerEnabled = $settings->bannerEnabled;
            $settingsRecord->bannerText = $settings->bannerText;
            $settingsRecord->bannerLinkText = $settings->bannerLinkText;
            $settingsRecord->bannerLink = $settings->bannerLink;

            if ($settingsRecord->save()) {
                Craft::$app->getSession()->setNotice(Craft::t('announce', 'Settings saved.'));
            } else {
                Craft::$app->getSession()->setError(Craft::t('announce', 'Couldn’t save settings.'));
            }
        } else {
            $errors = $settings->getErrors();

            foreach ($errors as $error) {
                Craft::$app->getSession()->setError($error[0]);
            }
        }
    }
}
