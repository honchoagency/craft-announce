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
    public string $modalTitle = '';
    public bool $modalEnabled = false;
    public string $bodyText = '';
    public string $linkButtonText = '';
    public string $linkButtonUrl = '';
    public string $buttonText = '';
    public string $buttonRedirectUrl = '';
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
            [['linkButtonUrl','bannerLink','buttonRedirectUrl'], UrlValidator::class],
        ]);
    }
}
