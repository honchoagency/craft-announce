<?php

namespace honchoagency\craftannounce\models;

use craft\base\Model;
use craft\validators\UrlValidator;

/**
 * Settings model
 */
class Settings extends Model
{
    // Public Properties
    public string $loginModalTitle = '';
    public bool $loginModalEnabled = false;
    public bool $loginModalAdminEnabled = true;
    public string $loginModalBodyText = '';
    public string $loginModalLinkText = '';
    public string $loginModalLink = '';
    public string $loginModalContinueButtonText = '';
    public string $loginModalContinueButtonURL = '';
    public bool $bannerEnabled = false;
    public string $bannerText = '';
    public string $bannerLinkText = '';
    public string $bannerLink = '';
    public bool $bannerLinkOpenInNewTab = true;

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
}
