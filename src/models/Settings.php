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
    public ?string $announcement = 'Your announcement here';
    public ?bool $announcementConfig = false;
    public ?bool $enabled = true;
    public ?bool $enabledConfig = false;
    public ?bool $adminEnabled = false;
    public ?bool $adminEnabledConfig = false;
    public ?string $bodyText = 'Your text here';
    public ?bool $bodyTextConfig = false;
    public ?string $linkText = 'Your link text here';
    public ?bool $linkTextConfig = false;
    public ?string $link = '';
    public ?bool $linkConfig = false;
    public ?string $continueButtonText = 'Continue';
    public ?string $continueButtonURL = '';
    public ?bool $continueButtonTextConfig = false;
    public ?bool $continueButtonURLConfig = false;
    public ?bool $alertEnabled = true;
    public ?bool $alertEnabledConfig = false;

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            [['announcement'], 'required'],
            [['link'], UrlValidator::class],
            [['continueButtonURL'], UrlValidator::class]
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
            $Settings->announcement = $SettingsRecord->announcement;
            $Settings->enabled = $SettingsRecord->enabled;
            $Settings->adminEnabled = $SettingsRecord->adminEnabled;
            $Settings->bodyText = $SettingsRecord->bodyText;
            $Settings->linkText = $SettingsRecord->linkText;
            $Settings->link = $SettingsRecord->link;
            $Settings->continueButtonText = $SettingsRecord->continueButtonText;
            $Settings->continueButtonURL = $SettingsRecord->continueButtonURL;
            $Settings->alertEnabled = $SettingsRecord->alertEnabled;
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

        $Settings->announcement = $requestParams['announcement'];
        $Settings->enabled = $requestParams['enabled'];
        $Settings->adminEnabled = $requestParams['adminEnabled'];
        $Settings->bodyText = $requestParams['bodyText'];
        $Settings->linkText = $requestParams['linkText'];
        $Settings->link = $requestParams['link'];
        $Settings->continueButtonText = $requestParams['continueButtonText'];
        $Settings->continueButtonURL = $requestParams['continueButtonURL'];
        $Settings->alertEnabled = $requestParams['alertEnabled'];

        $Settings = self::configSettingsOverride($Settings);

        if ($Settings->validate()) {
            $SettingsRecord = SettingsRecord::findOne(['handle' => 'settings']);

            if (!$SettingsRecord) {
                $SettingsRecord = new SettingsRecord();
                $SettingsRecord->handle = 'settings';
            }

            $SettingsRecord->announcement = $Settings->announcement;
            $SettingsRecord->enabled = $Settings->enabled;
            $SettingsRecord->adminEnabled = $Settings->adminEnabled;
            $SettingsRecord->bodyText = $Settings->bodyText;
            $SettingsRecord->linkText = $Settings->linkText;
            $SettingsRecord->link = $Settings->link;
            $SettingsRecord->continueButtonText = $Settings->continueButtonText;
            $SettingsRecord->continueButtonURL = $Settings->continueButtonURL;
            $SettingsRecord->alertEnabled = $Settings->alertEnabled;

            if ($SettingsRecord->save()) {
                Craft::$app->getSession()->setNotice(Craft::t('announcements', 'Settings saved.'));
            } else {
                Craft::$app->getSession()->setError(Craft::t('announcements', 'Couldn’t save settings.'));
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
            if (array_key_exists('announcement', $pluginConfig)) {
                $Settings->announcementConfig = true;
                $Settings->announcement = $pluginConfig['announcement'];
            }

            if (array_key_exists('enabled', $pluginConfig)) {
                $Settings->enabledConfig = true;
                $Settings->enabled = $pluginConfig['enabled'];
            }

            if (array_key_exists('adminEnabled', $pluginConfig)) {
                $Settings->adminEnabledConfig = true;
                $Settings->adminEnabled = $pluginConfig['adminEnabled'];
            }

            if (array_key_exists('bodyText', $pluginConfig)) {
                $Settings->bodyTextConfig = true;
                $Settings->bodyText = $pluginConfig['bodyText'];
            }

            if (array_key_exists('linkText', $pluginConfig)) {
                $Settings->linkTextConfig = true;
                $Settings->linkText = $pluginConfig['linkText'];
            }

            if (array_key_exists('link', $pluginConfig)) {
                $Settings->linkConfig = true;
                $Settings->link = $pluginConfig['link'];
            }

            if (array_key_exists('continueButtonText', $pluginConfig)) {
                $Settings->continueButtonTextConfig = true;
                $Settings->continueButtonText = $pluginConfig['continueButtonText'];
            }

            if (array_key_exists('continueButtonURL', $pluginConfig)) {
                $Settings->continueButtonURLConfig = true;
                $Settings->continueButtonURL = $pluginConfig['continueButtonURL'];
            }

            if (array_key_exists('alertEnabled', $pluginConfig)) {
                $Settings->alertEnabledConfig = true;
                $Settings->alertEnabled = $pluginConfig['alertEnabled'];
            }
        }

        return $Settings;
    }
}
