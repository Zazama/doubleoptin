<?php

namespace Zazama\DoubleOptIn\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\UserForms\Model\Submission\SubmittedForm;
use Zazama\DoubleOptIn\Models\EmailVerification;

/**
 * @property int $EmailVerificationID
 * @method EmailVerification EmailVerification()
 * @method (SubmittedForm & static) getOwner()
 */
class SubmittedFormExtension extends Extension
{
    private static $has_one = [
        'EmailVerification' => EmailVerification::class
    ];

    public function canView($member = null)
    {
        if ($this->getOwner()->Parent()) {
            if ($this->getOwner()->Parent()->EnableDoubleOptIn) {
                if ($this->getOwner()->EmailVerification()->Verified || !$this->getOwner()->Parent()->DoubleOptInFieldID) {
                    return $this->getOwner()->Parent()->canView($member);
                } else {
                    return false;
                }
            } else {
                return $this->getOwner()->Parent()->canView($member);
            }
        }
        return false;
    }
}
