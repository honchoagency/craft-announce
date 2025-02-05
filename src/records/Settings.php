<?php

namespace honcho\craftannounce\records;

use Craft;
use craft\db\ActiveRecord;

/**
 * Settings record
 */
class Settings extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%honcho_announce_settings}}';
    }
}
