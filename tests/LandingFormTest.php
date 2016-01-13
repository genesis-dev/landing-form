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
}