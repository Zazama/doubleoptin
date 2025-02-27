<?php

namespace Zazama\DoubleOptIn\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\UserForms\Model\EditableFormField\EditableEmailField;
use SilverStripe\UserForms\Model\UserDefinedForm;

/**
 * @property bool $EnableDoubleOptIn
 * @property ?string $DoubleOptInSubject
 * @property int $DoubleOptInFieldID
 * @method EditableEmailField DoubleOptInField()
 * @method (UserDefinedForm & static) getOwner()
 */
class UserDefinedFormExtension extends Extension
{
    private static $db = [
        'EnableDoubleOptIn'  => 'Boolean(0)',
        'DoubleOptInSubject' => 'Varchar'
    ];

    private static $has_one = [
        'DoubleOptInField' => EditableEmailField::class
    ];

    public function updateFormOptions($options)
    {
        $options->add(CheckboxField::create('EnableDoubleOptIn', _t(self::class . '.Enable', 'Enable Double-Opt-In')));
        $options->add(DropdownField::create('DoubleOptInFieldID', _t(self::class . '.Field', 'Double-Opt-In E-Mail field'), EditableEmailField::get()->where(['ParentID' => $this->getOwner()->ID]))->setEmptyString(''));
        $options->add(TextField::create('DoubleOptInSubject', _t(self::class . '.Subject', 'Verification Subject')));
        return $options;
    }
}
