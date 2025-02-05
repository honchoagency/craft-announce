<?php

namespace honcho\craftannounce\models;

use Craft;
use craft\base\Model;
use craft\validators\UrlValidator;
use honcho\craftannounce\Plugin;
use honcho\craftannounce\records\Settings as SettingsRecord;

/**
 * Settings model
 */
class Settings extends Model
{
    // Public Properties
    public ?string $loginModalTitle = 'Your announcement here';
    public ?bool $loginModalTitleConfig = false;
    public ?bool $loginModalEnabled = true;
    public ?bool $loginModalEnabledConfig = false;
    public ?bool $loginModalAdminDisabled = false;
    public ?bool $loginModalAdminDisabledConfig = false;
    public ?string $loginModalBodyText = 'Your text here';
    public ?bool $loginModalBodyTextConfig = false;
    public ?string $loginModalLinkText = 'Your link text here';
    public ?bool $loginModalLinkTextConfig = false;
    public ?string $loginModalLink = '';
    public ?bool $loginModalLinkConfig = false;
    public ?string $loginModalContinueButtonText = 'Continue';
    public ?string $loginModalContinueButtonURL = '';
    public ?bool $loginModalContinueButtonTextConfig = false;
    public ?bool $loginModalContinueButtonURLConfig = false;
    public ?bool $bannerEnabled = true;
    public ?bool $bannerEnabledConfig = false;
    public ?string $bannerText = 'Your banner text here';
    public ?bool $bannerTextConfig = false;
    public ?string $bannerLinkText = 'Your banner link text here';
    public ?bool $bannerLinkTextConfig = false;
    public ?string $bannerLink = '';
    public ?bool $bannerLinkConfig = false;

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
        $Settings = new Settings();
        $SettingsRecord = SettingsRecord::findOne(['handle' => 'settings']);

        if ($SettingsRecord) {
            $Settings->loginModalTitle = $SettingsRecord->loginModalTitle;
            $Settings->loginModalEnabled = $SettingsRecord->loginModalEnabled;
            $Settings->loginModalAdminDisabled = $SettingsRecord->loginModalAdminDisabled;
            $Settings->loginModalBodyText = $SettingsRecord->loginModalBodyText;
            $Settings->loginModalLinkText = $SettingsRecord->loginModalLinkText;
            $Settings->loginModalLink = $SettingsRecord->loginModalLink;
            $Settings->loginModalContinueButtonText = $SettingsRecord->loginModalContinueButtonText;
            $Settings->loginModalContinueButtonURL = $SettingsRecord->loginModalContinueButtonURL;
            $Settings->bannerEnabled = $SettingsRecord->bannerEnabled;
            $Settings->bannerText = $SettingsRecord->bannerText;
            $Settings->bannerLinkText = $SettingsRecord->bannerLinkText;
            $Settings->bannerLink = $SettingsRecord->bannerLink;
        }

        return self::configSettingsOverride($Settings);
    }

    /**
     * Save the settings
     *
     * @return void
     */
    static public function saveSettings(): void
    {
        $Settings = new Settings();
        $requestParams = Craft::$app->getRequest()->getBodyParams();

        $Settings->loginModalTitle = $requestParams['loginModalTitle'];
        $Settings->loginModalEnabled = $requestParams['loginModalEnabled'];
        $Settings->loginModalAdminDisabled = $requestParams['loginModalAdminDisabled'];
        $Settings->loginModalBodyText = $requestParams['loginModalBodyText'];
        $Settings->loginModalLinkText = $requestParams['loginModalLinkText'];
        $Settings->loginModalLink = $requestParams['loginModalLink'];
        $Settings->loginModalContinueButtonText = $requestParams['loginModalContinueButtonText'];
        $Settings->loginModalContinueButtonURL = $requestParams['loginModalContinueButtonURL'];
        $Settings->bannerEnabled = $requestParams['bannerEnabled'];
        $Settings->bannerText = $requestParams['bannerText'];
        $Settings->bannerLinkText = $requestParams['bannerLinkText'];
        $Settings->bannerLink = $requestParams['bannerLink'];

        $Settings = self::configSettingsOverride($Settings);

        if ($Settings->validate()) {
            $SettingsRecord = SettingsRecord::findOne(['handle' => 'settings']);

            if (!$SettingsRecord) {
                $SettingsRecord = new SettingsRecord();
                $SettingsRecord->handle = 'settings';
            }

            $SettingsRecord->loginModalTitle = $Settings->loginModalTitle;
            $SettingsRecord->loginModalEnabled = $Settings->loginModalEnabled;
            $SettingsRecord->loginModalAdminDisabled = $Settings->loginModalAdminDisabled;
            $SettingsRecord->loginModalBodyText = $Settings->loginModalBodyText;
            $SettingsRecord->loginModalLinkText = $Settings->loginModalLinkText;
            $SettingsRecord->loginModalLink = $Settings->loginModalLink;
            $SettingsRecord->loginModalContinueButtonText = $Settings->loginModalContinueButtonText;
            $SettingsRecord->loginModalContinueButtonURL = $Settings->loginModalContinueButtonURL;
            $SettingsRecord->bannerEnabled = $Settings->bannerEnabled;
            $SettingsRecord->bannerText = $Settings->bannerText;
            $SettingsRecord->bannerLinkText = $Settings->bannerLinkText;
            $SettingsRecord->bannerLink = $Settings->bannerLink;

            if ($SettingsRecord->save()) {
                Craft::$app->getSession()->setNotice(Craft::t('announce', 'Settings saved.'));
            } else {
                Craft::$app->getSession()->setError(Craft::t('announce', 'Couldn’t save settings.'));
            }
        } else {
            $errors = $Settings->getErrors();

            foreach ($errors as $error) {
                Craft::$app->getSession()->setError($error[0]);
            }
        }
    }

    /**
     * Update/override settings from config if set
     *
     * @param Settings $Settings
     * @param array $pluginConfig
     * @return Settings
     */
    static private function configSettingsOverride(Settings $Settings): Settings
    {
        $pluginConfig = Plugin::getInstance()->pluginConfig;

        if ($pluginConfig != []) {
            if (array_key_exists('loginModalTitle', $pluginConfig)) {
                $Settings->loginModalTitleConfig = true;
                $Settings->loginModalTitle = $pluginConfig['loginModalTitle'];
            }

            if (array_key_exists('loginModalEnabled', $pluginConfig)) {
                $Settings->loginModalEnabledConfig = true;
                $Settings->loginModalEnabled = $pluginConfig['loginModalEnabled'];
            }

            if (array_key_exists('loginModalAdminDisabled', $pluginConfig)) {
                $Settings->loginModalAdminDisabledConfig = true;
                $Settings->loginModalAdminDisabled = $pluginConfig['loginModalAdminDisabled'];
            }

            if (array_key_exists('loginModalBodyText', $pluginConfig)) {
                $Settings->loginModalBodyTextConfig = true;
                $Settings->loginModalBodyText = $pluginConfig['loginModalBodyText'];
            }

            if (array_key_exists('loginModalLinkText', $pluginConfig)) {
                $Settings->loginModalLinkTextConfig = true;
                $Settings->loginModalLinkText = $pluginConfig['loginModalLinkText'];
            }

            if (array_key_exists('loginModalLink', $pluginConfig)) {
                $Settings->loginModalLinkConfig = true;
                $Settings->loginModalLink = $pluginConfig['loginModalLink'];
            }

            if (array_key_exists('loginModalContinueButtonText', $pluginConfig)) {
                $Settings->loginModalContinueButtonTextConfig = true;
                $Settings->loginModalContinueButtonText = $pluginConfig['loginModalContinueButtonText'];
            }

            if (array_key_exists('loginModalContinueButtonURL', $pluginConfig)) {
                $Settings->loginModalContinueButtonURLConfig = true;
                $Settings->loginModalContinueButtonURL = $pluginConfig['loginModalContinueButtonURL'];
            }

            if (array_key_exists('bannerEnabled', $pluginConfig)) {
                $Settings->bannerEnabledConfig = true;
                $Settings->bannerEnabled = $pluginConfig['bannerEnabled'];
            }

            if (array_key_exists('bannerText', $pluginConfig)) {
                $Settings->bannerTextConfig = true;
                $Settings->bannerText = $pluginConfig['bannerText'];
            }

            if (array_key_exists('bannerLinkText', $pluginConfig)) {
                $Settings->bannerLinkTextConfig = true;
                $Settings->bannerLinkText = $pluginConfig['bannerLinkText'];
            }

            if (array_key_exists('bannerLink', $pluginConfig)) {
                $Settings->bannerLinkConfig = true;
                $Settings->bannerLink = $pluginConfig['bannerLink'];
            }
        }

        return $Settings;
    }
}
