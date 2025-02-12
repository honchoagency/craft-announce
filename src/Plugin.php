<?php
/**
 * Announce plugin for Craft CMS
 *
 * Announce allows you to display an alert banner on the control panel
 * and display a announcement after the login page.
 *
 * @link      https://honcho.agency/
 * @copyright Copyright (c) 2025 Honcho
 */

namespace honchoagency\craftannounce;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\events\RegisterCpAlertsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\helpers\Cp as CpHelper;
use craft\helpers\UrlHelper;
use craft\web\UrlManager;
use honchoagency\craftannounce\services\Settings;
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
            'label' => Craft::t('announce','Announce'),
            'icon' => CRAFT_BASE_PATH . '/plugins/announce/src/icon-mask.svg',
        ];

        if (!Craft::$app->getUser()->checkPermission('accessPlugin-announce')) {
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
                $event->rules['login-announcement'] = 'announce/announce/login-announcement';

                // check user has permission for announcements
                if (!Craft::$app->getUser()->checkPermission('accessPlugin-announce')) {
                    return;
                }
                $event->rules['announce'] = 'announce/settings/settings';
            }
        );

        Event::on(
            CpHelper::class,
            CpHelper::EVENT_REGISTER_ALERTS,
            function (RegisterCpAlertsEvent $event) {
                $settings = $this->settings->getSettings();

                if (!$settings->bannerEnabled || !$settings->bannerText) {
                    return;
                }

                $alertHTML = '<svg width="18" viewBox="0 0 49 46" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_6383_2288)"><path d="M49 32.7794L27.9041 0L23.4437 2.8415L24.8036 4.95561L5.54232 31.0268L4.46465 29.3508L0.00427246 32.1923L7.87729 44.4261L12.3377 41.5846L11.2899 39.9554L17.9826 38.6027C18.0297 38.6836 18.0724 38.7644 18.1195 38.8452L20.2791 42.1419C21.8015 44.4516 24.3503 45.8852 27.1258 45.9957C29.8969 46.1063 32.5569 44.8813 34.2632 42.6991C35.9653 40.5212 36.5084 37.6627 35.7258 35.0168L39.6601 34.2214L43.184 33.5068L44.5482 35.6251L49.0085 32.7836L49 32.7794ZM12.389 30.6397L27.8741 9.69003L30.8035 14.2458L12.389 30.6397ZM29.4522 40.5339C27.7801 41.5931 25.5563 41.1082 24.4872 39.445L23.2726 37.5308L30.7907 36.008C31.5476 37.6371 30.9746 39.5726 29.4522 40.5339Z" fill="#d81f23"/></g></svg> ';

                $alertHTML .= $settings->bannerText;

                if ($settings->bannerLink && $settings->bannerLinkText) {
                    $alertHTML .= ' <a href="' . $settings->bannerLink . '" class="go">' . $settings->bannerLinkText . '</a>';
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
            User::class,
            User::EVENT_AFTER_LOGIN,
            function() {
                $request = Craft::$app->getRequest();
                $user = Craft::$app->getUser();

                if ($request->getIsCpRequest()) {
                    $settings = $this->settings->getSettings();

                    if ($settings->modalEnabled) {
                        $user->setReturnUrl(UrlHelper::cpUrl('login-announcement'));
                    }
                }
            }
        );
    }
}
