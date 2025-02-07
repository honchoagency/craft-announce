<?php

namespace honchoagency\craftannounce\controllers;

use craft\web\Controller;
use yii\web\Response;
use honchoagency\craftannounce\services\Settings as SettingsService;

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
        $Settings = SettingsService::getSettings();

        return $this->renderTemplate('announce/_settings', [
            'settings' => $Settings,
        ]);
    }

    /**
     * settings/save action
     */
    public function actionSaveSettings(): void
    {
        $this->requirePostRequest();
        SettingsService::saveSettings();
    }
}
