<?php

namespace Zazama\DoubleOptIn\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\UserForms\Control\UserDefinedFormController;
use SilverStripe\UserForms\Model\Submission\SubmittedFormField;
use Zazama\DoubleOptIn\Models\EmailDummy;
use Zazama\DoubleOptIn\Models\EmailVerification;
use Zazama\DoubleOptIn\Models\UserFormEmailToSend;

/**
 * @method (UserDefinedFormController & static) getOwner()
 */
class UserDefinedFormControllerExtension extends Extension
{
    public function updateEmail(&$email, $recipient, $emailData)
    {
        $referenceField = $emailData['Fields'][0];
        $submittedForm = $referenceField->Parent();
        $page = $submittedForm->Parent();
        if ($page->EnableDoubleOptIn && $page->DoubleOptInFieldID) {
            $emailToSend = UserFormEmailToSend::create();
            $emailToSend->setData($email, $recipient, $emailData);
            $emailToSend->SubmittedFormID = $emailData['Fields'][0]->ParentID;
            $emailToSend->write();
            $email = EmailDummy::create();
        } else {
            return;
        }

        if ($page && $page->EnableDoubleOptIn && $page->DoubleOptInFieldID) {
            $emailField = SubmittedFormField::get()->filter([
                'Name'     => $page->DoubleOptInField()->Name,
                'ParentID' => $submittedForm->ID
            ])->limit(1)[0];
            if (!$emailField) {
                return;
            }
            $emailVerification = EmailVerification::create();
            $emailVerification->init($emailField->Value);
            $submittedForm->EmailVerificationID = $emailVerification->ID;
            $emailVerification->SubmittedFormID = $submittedForm->ID;
            $emailVerification->send($page->DoubleOptInSubject);
            $emailVerification->write();
            $submittedForm->write();
        }
    }
}
