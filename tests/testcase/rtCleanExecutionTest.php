<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtCleanExecutionTest extends PHPUnit_Framework_TestCase
{
    private $path_to_tests;
    private $sample_test;
    private $sample_fail;

    public function setUp()
    {
        $this->php = trim(shell_exec("which php"));

        $this->path_to_tests = realpath(dirname(__FILE__) . '/../../phpt-tests');
        $this->sample_test = $this->path_to_tests . '/sample_clean.phpt';
        $this->sample_fail = $this->path_to_tests . '/sample_clean_fail.phpt';
    }

    public function tearDown()
    {
        @unlink($this->path-to_tests . '/sample_clean.clean.php');
        @unlink($this->path-to_tests . '/phpt-tests/sample_clean_fail.clean.php');
        @unlink($this->path-to_tests . '/cleantest.tmp');
    }

    public function testFileRun()
    { 
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', $this->php, $this->sample_test));
        $config->configure();

        //Retrieve the array of test file names
        $testFiles = $config->getSetting('TestFiles');

        //Read the test file
        $testFile = new rtPhpTestFile();
        $testFile->doRead($testFiles[0]);
        $testFile->normaliseLineEndings();

        //Create a new test case
        $status = new rtTestStatus();
        $testCase = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $config, $status);      

        //Setup and set the local environment for the test case
        $testCase->executeTest($config);

        //Check that the temp file has been removed
        $fileName = $this->path_to_tests . '/phpt-tests/cleantest.tmp';

        $this->assertFalse(file_exists($fileName));          
    }

    public function testFailedClean()
    { 
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', $this->php, $this->sample_fail));
        $config->configure();

        //Retrieve the array of test file names
        $testFiles = $config->getSetting('TestFiles');

        //Read the test file
        $testFile = new rtPhpTestFile();
        $testFile->doRead($testFiles[0]);
        $testFile->normaliseLineEndings();

        //Create a new test case
        $status = new rtTestStatus();
        $testCase = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $config, $status);      

        //Setup and set the local environment for the test case
        $testCase->executeTest($config);

        //Check that the temp file has been removed
        $fileName = $this->path_to_tests . '/cleantest.tmp';

        $this->assertTrue(file_exists($fileName));          
    }
}  
?>
