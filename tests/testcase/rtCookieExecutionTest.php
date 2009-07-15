<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtCookieExecutionTest extends PHPUnit_Framework_TestCase
{
    private $path_to_tests;
    private $sample_test;
    private $sample_fail;

    public function setUp()
    {
        $this->php = trim(shell_exec("which php"));
        $this->php_cgi = trim(shell_exec("which php-cgi"));

        $this->path_to_tests = realpath(dirname(__FILE__) . '/../../phpt-tests');
        $this->sample_test = $this->path_to_tests . '/sample_cookie.phpt';
    }

    public function tearDown()
    {
        @unlink($this->path-to_tests . '/sample_cookie.php');
    }

    public function testFileRun()
    { 
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', $this->php, $this->sample_test));
        $config->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE',$this->php_cgi);
        $config->configure();

        //Retrieve the array of test file names
        $testFiles = $config->getSetting('TestFiles');

        //Read the test file
        $testFile = new rtPhpTestFile();
        $testFile->doRead($testFiles[0]);
        $testFile->normaliseLineEndings();
        $testFile->removeEmptySections();

        //Create a new test case
        $status = new rtTestStatus($testFile->getTestName());
        $testCase = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $config, $status);      

        //Setup and set the local environment for the test case
        $testCase->executeTest($config);
       
        $this->assertFalse($testCase->getStatus()->getValue('fail'));        

    }
}  
?>
