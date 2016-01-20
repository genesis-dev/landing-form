<?php
class LandingFormTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unknown resource.
     */
    public function testExceptionHasRightSiteID()
    {
        $_GET['siteID'] = "some other ID";
        $form = new LandingForm();
    }

    public function testValidate() {
        $_GET['siteID'] = "test_all";
        $_GET['landingForm']['email'] = 'glmeist@gmail.com';
        $_GET['landingForm']['phone'] = '+77012240824';
        $valid_form = new LandingForm();
        $valid_form->load();
        $this->assertTrue($valid_form->validate());
        $_GET['landingForm']['phone'] = '87012240824';
        $valid_form2 = new LandingForm();
        $valid_form2->load();
        $this->assertTrue($valid_form2->validate());
        $_GET['landingForm']['email'] = 'not_email';
        $_GET['landingForm']['phone'] = '+77012240824321';
        $invalid_form = new LandingForm();
        $invalid_form->load();
        $this->assertFalse($invalid_form->validate());
        unset($_GET['landingForm']);
        $empty_form = new LandingForm();
        $empty_form->load();
        $this->assertFalse($empty_form->validate());
        $_GET['landingForm']['name'] = 'Name';
        $invalid_form2 = new LandingForm();
        $invalid_form2->load();
        $this->assertFalse($invalid_form2->validate());
        $_GET['landingForm']['phone'] = '87017777777';
        $valid_form3 = new LandingForm();
        $valid_form3->load();
        $this->assertTrue($valid_form3->validate());
        unset($_GET['landingForm']);
        $_GET['landingForm']['email'] = 'glmeist@gmail.com';
        $valid_form4 = new LandingForm();
        $valid_form4->load();
        $this->assertTrue($valid_form4->validate());
    }

    public function testSendAndSave() {
        $tests = ["test_null", "test_mail", "test_telegram", "test_all"];
        foreach ($tests as $test) {
            $_GET['siteID'] = $test;
            $_GET['landingForm']['email'] = 'glmeist@gmail.com';
            $_GET['landingForm']['phone'] = '+77012240824';
            $_GET['landingForm']['fieldNames']['phone'] = "Телефон";
            $valid_form = new LandingForm();
            $valid_form->load();
            if ($valid_form->getSiteConfig()['mailer'] !== false)
                $this->assertTrue($valid_form->send());
            else
                $this->assertNull($valid_form->send());
            $this->assertGreaterThan(0, $valid_form->save());
            unset($valid_form);
        }
    }

    public function testSendTelegram() {
        $tests = ["test_null", "test_mail", "test_telegram", "test_all"];
        foreach ($tests as $test) {
            $_GET['siteID'] = $test;
            $_GET['landingForm']['email'] = 'glmeist@gmail.com';
            $_GET['landingForm']['phone'] = '+77012240824';
            $_GET['landingForm']['fieldNames']['phone'] = "Телефон";
            $valid_form = new LandingForm();
            $valid_form->load();
            if (isset($valid_form->getSiteConfig()["telegram"]["channel_name"])) {
                $telegram = $valid_form->sendTelegram();
                $this->assertTrue($telegram->ok);
            } else
                $this->assertNull($valid_form->sendTelegram());
            unset($valid_form);
        }
    }
}