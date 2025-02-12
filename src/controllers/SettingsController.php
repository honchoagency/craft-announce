<?php

namespace honchoagency\craftannounce\controllers;

use craft\web\Controller;
use yii\web\Response;
use honchoagency\craftannounce\Plugin;
use Craft;
use honchoagency\craftannounce\records\Settings as SettingsRecord;

/**
 * Settings controller
 */
class SettingsController extends Controller
{
    protected array|int|bool $allowAnonymous = self::ALLOW_ANONYMOUS_NEVER;

    /**
     * announcements/announcements action
     */
    public function actionSettings(): Response
    {
        $settings = Plugin::getInstance()->settings->getSettings();
        $config = Plugin::getInstance()->pluginConfig;

        return $this->renderTemplate('announce/_settings', [
            'settings' => $settings,
            'config' => $config,
        ]);
    }

    /**
     * settings/save action
     */
    public function actionSaveSettings(): Response
    {
        $this->requirePostRequest();
        $settings = Plugin::getInstance()->settings->getSettings();
        $requestParams = Craft::$app->getRequest()->getBodyParams();

        $settings->modalTitle = $requestParams['modalTitle'];
        $settings->modalEnabled = $requestParams['modalEnabled'];
        $settings->bodyText = $requestParams['bodyText'];
        $settings->linkButtonText = $requestParams['linkButtonText'];
        $settings->linkButtonUrl = $requestParams['linkButtonUrl'];
        $settings->buttonText = $requestParams['buttonText'];
        $settings->buttonRedirectUrl = $requestParams['buttonRedirectUrl'];
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

            $settingsRecord->modalTitle = $settings->modalTitle;
            $settingsRecord->modalEnabled = $settings->modalEnabled;
            $settingsRecord->bodyText = $settings->bodyText;
            $settingsRecord->linkButtonText = $settings->linkButtonText;
            $settingsRecord->linkButtonUrl = $settings->linkButtonUrl;
            $settingsRecord->buttonText = $settings->buttonText;
            $settingsRecord->buttonRedirectUrl = $settings->buttonRedirectUrl;
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

        $config = Plugin::getInstance()->pluginConfig;

        return $this->renderTemplate('announce/_settings', [
            'settings' => $settings,
            'config' => $config,
        ]);
    }
}
