<?php

namespace honchoagency\craftannounce\web\assets\loginannouncement;

use Craft;
use craft\web\AssetBundle;

/**
 * Login Announcement asset bundle
 */
class LoginAnnouncementAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/dist';
    public $depends = [];
    public $js = ['js/login-announcement.js'];
    public $css = ['css/login-announcement.css'];
}
