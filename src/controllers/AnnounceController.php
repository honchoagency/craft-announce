<?php

namespace honcho\craftannounce\controllers;

use craft\web\Controller;
use yii\web\Response;
use honcho\craftannounce\services\Settings as SettingsService;

/**
 * Announce controller
 */
class AnnounceController extends Controller
{
    protected array|int|bool $allowAnonymous = self::ALLOW_ANONYMOUS_NEVER;

    /**
     * Welcome announcement.
     * Show the welcome announcement depending on the settings
     * for admin and non-admin users.
     *
     * @return Response
     */
    public function actionWelcomeAnnouncement(): Response
    {
        return $this->renderTemplate('announce/_welcome-announcement', [
            'settings' => SettingsService::getSettings(),
        ]);
    }
}
