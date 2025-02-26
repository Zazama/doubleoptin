<?php

namespace Zazama\DoubleOptIn\Tests;

use SilverStripe\Dev\FunctionalTest;
use Zazama\DoubleOptIn\Models\EmailVerification;
use Zazama\DoubleOptIn\Tests\Src\VerificationTestPage;

class VerificationControllerTest extends FunctionalTest
{
    protected static $fixture_file = 'VerificationControllerTest.yml';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testVerificationControllerIndex()
    {
        $page = $this->objFromFixture(VerificationTestPage::class, 'test');
        $page->publishSingle();
        $this->assertStringContainsString('Your token is invalid. Please try clicking the link again.', $this->get('verify-test')->getBody());
        $this->assertStringContainsString('Your token is invalid. Please try clicking the link again.', $this->get('verify-test?token=SKdhakojbwedbklas')->getBody());

        $verified = $this->objFromFixture(EmailVerification::class, 'verified');
        $verified->write();
        $this->assertStringContainsString('Your E-Mail address was already verified.', $this->get('verify-test?token=' . $verified->Token)->getBody());

        $unverified = $this->objFromFixture(EmailVerification::class, 'unverified');
        $unverified->write();
        $this->assertStringContainsString('You have successfully verified your E-Mail address.', $this->get('verify-test?token=' . $unverified->Token)->getBody());
    }
}
