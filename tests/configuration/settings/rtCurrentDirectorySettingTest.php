<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtCurrentDirectorySettingTest extends PHPUnit_Framework_TestCase
{
    public function testSetting()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', 'test.phpt'));
        $dirsetting  = new rtCurrentDirectorySetting($configuration);

        $this->assertEquals(getcwd(), $dirsetting->get());
    }   
}
?>
