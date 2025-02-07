<?php

namespace honchoagency\craftannounce\controllers;

use craft\web\Controller;
use yii\web\Response;
use honchoagency\craftannounce\services\Settings as SettingsService;
use honchoagency\craftannounce\Plugin;


/**
 * Announce controller
 */
class AnnounceController extends Controller
{
    protected array|int|bool $allowAnonymous = self::ALLOW_ANONYMOUS_NEVER;

    /**
     * Login announcement.
     * Show the login announcement depending on the settings
     * for admin and non-admin users.
     *
     * @return Response
     */
    public function actionLoginAnnouncement(): Response
    {
        $settings = Plugin::getInstance()->settings->getSettings();

        return $this->renderTemplate('announce/_login-announcement', [
            'settings' => $settings,
        ]);
    }
}
