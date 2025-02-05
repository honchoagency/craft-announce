<?php
/**
 * Announce plugin for Craft CMS
 *
 * Announce allows you to display an alert banner on the control panel
 * and display a welcome announcement after the login page.
 *
 * @link      https://honcho.agency/
 * @copyright Copyright (c) 2025 Honcho
 */

namespace honcho\craftannounce;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\events\RegisterCpAlertsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\helpers\Cp as CpHelper;
use craft\helpers\UrlHelper;
use craft\services\UserPermissions;
use craft\web\UrlManager;
use honcho\craftannounce\services\Settings;
use yii\base\Event;
use yii\web\User;

/**
 * Announce plugin
 *
 * @method static Plugin getInstance()
 * @author Honcho <hello@honcho.agency>
 * @copyright Honcho
 * @license https://craftcms.github.io/license/ Craft License
 * @property-read Settings $settings
 */
class Plugin extends BasePlugin
{
    public string $schemaVersion = '1.0.0';
    public array $pluginConfig = [];
    public bool $hasCpSettings = true;
    public bool $hasCpSection = true;

    public static function config(): array
    {
        return [
            'components' => ['settings' => Settings::class],
        ];
    }

    public function init(): void
    {
        parent::init();

        $this->attachEventHandlers();

        // Any code that creates an element query or loads Twig should be deferred until
        // after Craft is fully initialized, to avoid conflicts with other plugins/modules
        Craft::$app->onInit(function() {
            $this->getPluginConfig();
        });
    }

    /**
     * get the plugin config
     */
    private function getPluginConfig(): void
    {
        $this->pluginConfig = Craft::$app->config->getConfigFromFile('announce');
    }

    /**
     * @inheritdoc
     */
    public function getSettingsResponse(): mixed
    {
        // Redirect to the custom settings page
        return Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('announce'));
    }

    /**
     * @inheritdoc
     */
    public function getCpNavItem(): ?array
    {
        $navItem = parent::getCpNavItem();

        $navItem = [
            'url' => 'announce',
            'label' => 'Announce',
            'icon' => CRAFT_BASE_PATH . '/plugins/announce/src/icon.svg',
        ];

        if (!Craft::$app->getUser()->checkPermission('announce')) {
            return $navItem = null;
        }

        return $navItem;
    }

    private function attachEventHandlers(): void
    {
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
                $event->rules['welcome-announcement'] = 'announce/announce/welcome-announcement';

                // check user has permission for announcements
                if (!Craft::$app->getUser()->checkPermission('announce')) {
                    return;
                }
                $event->rules['announce'] = 'announce/settings/settings';
            }
        );

        Event::on(
            CpHelper::class,
            CpHelper::EVENT_REGISTER_ALERTS,
            function (RegisterCpAlertsEvent $event) {
                $settings = settings::getSettings();

                if (!$settings->alertEnabled) {
                    return;
                }

                $alertHTML = '<svg width="28" height="28" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.8907 51.1687C15.0018 50.0535 15.0018 48.2528 13.8907 47.1377L13.2897 46.5367L20.045 43.5363C20.1149 43.6124 20.1805 43.6928 20.2503 43.769L23.2957 46.7631C25.4394 48.858 28.4616 49.7869 31.4114 49.2642C34.3615 48.7395 36.8779 46.8245 38.1711 44.1202C39.462 41.416 39.3689 38.257 37.9235 35.633L45.4384 32.313L47.1336 33.991C48.2468 35.104 50.0519 35.104 51.1651 33.991C52.2783 32.878 52.2783 31.0731 51.1651 29.96L22.0448 0.833181C20.9274 -0.279837 19.1201 -0.277725 18.007 0.839529C16.8938 1.95466 16.898 3.7617 18.0133 4.87471L19.427 6.28821L5.16323 38.4112L4.87541 38.1128C3.76223 36.9977 1.95494 36.9956 0.839643 38.1086C-0.277762 39.2216 -0.279875 41.0265 0.833294 42.1438L9.85922 51.1684C10.9745 52.2772 12.7754 52.2775 13.8907 51.1687ZM32.5986 42.9355C31.0812 44.4484 28.6264 44.4484 27.1089 42.9355L25.365 41.1686L32.9498 37.8211C34.1371 39.3721 33.9868 41.5643 32.5985 42.9397L32.5986 42.9355ZM23.7613 10.6115L27.9113 14.761L12.3034 36.4011L23.7613 10.6115Z" fill="#d81f23"/></svg>   ';

                $alertHTML .= '<strong>' . $settings->announcement . '</strong>';

                if ($settings->bodyText) {
                    $alertHTML .= ' ' . $settings->bodyText;
                }

                if ($settings->link && $settings->linkText) {
                    $alertHTML .= ' <a href="' . $settings->link . '" target="_blank" class="go">' . $settings->linkText . '</a>';
                }

                $event->alerts = array_merge($event->alerts, [
                    [
                        'content' => $alertHTML,
                        'showIcon' => false,
                    ],
                ]);
            }
        );

        Event::on(
            UserPermissions::class,
            UserPermissions::EVENT_REGISTER_PERMISSIONS,
            function (RegisterUserPermissionsEvent $event) {
                $event->permissions[] = [
                    'heading' => 'Announce',
                    'permissions' => [
                        'announce' => [
                            'label' => 'Access Announce',
                        ],
                    ],
                ];
            }
        );

        Event::on(
            User::class,
            User::EVENT_AFTER_LOGIN,
            function() {
                $request = Craft::$app->getRequest();
                $userSession = Craft::$app->getUser();

                if ($request->getIsCpRequest()) {
                    $userIsAdmin = Craft::$app->getUser()->getIsAdmin();
                    $Settings = settings::getSettings();

                    // only redirect for users enabled to see the announcement
                    if (($Settings->enabled && !$userIsAdmin) || ($Settings->adminEnabled && $userIsAdmin)) {
                        $userSession->setReturnUrl(UrlHelper::cpUrl('welcome-announcement'));
                    } else {
                        $userSession->setReturnUrl(UrlHelper::cpUrl('dashboard'));
                    }
                }
            }
        );
    }
}
