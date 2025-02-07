<?php

namespace honchoagency\craftannounce\controllers;

use craft\web\Controller;
use yii\web\Response;
use honchoagency\craftannounce\Plugin;

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
        $Settings = Plugin::getInstance()->settings->getSettings();
        $config = Plugin::getInstance()->pluginConfig;

        return $this->renderTemplate('announce/_settings', [
            'settings' => $Settings,
            'config' => $config,
        ]);
    }

    /**
     * settings/save action
     */
    public function actionSaveSettings(): void
    {
        $this->requirePostRequest();
        Plugin::getInstance()->settings->saveSettings();
    }
}
