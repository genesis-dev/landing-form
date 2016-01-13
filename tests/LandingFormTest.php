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

    public function testValidateEmailAndPhone() {
        $_GET['siteID'] = "h9apn";
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
    }
}