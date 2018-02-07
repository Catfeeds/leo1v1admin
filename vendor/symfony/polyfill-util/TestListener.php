<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Polyfill\Util;

<<<<<<< HEAD
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestListener as TestListenerInterface;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;

if (class_exists('PHPUnit_Runner_Version') && version_compare(\PHPUnit_Runner_Version::id(), '6.0.0', '<')) {
    class_alias('Symfony\Polyfill\Util\LegacyTestListener', 'Symfony\Polyfill\Util\TestListener');
// Using an early return instead of a else does not work when using the PHPUnit phar due to some weird PHP behavior (the class
// gets defined without executing the code before it and so the definition is not properly conditional)
} else {
    /**
     * @author Nicolas Grekas <p@tchwork.com>
     */
    class TestListener extends TestSuite implements TestListenerInterface
    {
        private $suite;
        private $trait;

        public function __construct(TestSuite $suite = null)
        {
            if ($suite) {
                $this->suite = $suite;
                $this->setName($suite->getName().' with polyfills enabled');
                $this->addTest($suite);
            }
            $this->trait = new TestListenerTrait();
        }

        public function startTestSuite(TestSuite $suite)
        {
            $this->trait->startTestSuite($suite);
        }

        protected function setUp()
        {
            TestListenerTrait::$enabledPolyfills = $this->suite->getName();
        }

        protected function tearDown()
        {
            TestListenerTrait::$enabledPolyfills = false;
        }

        public function addError(Test $test, \Exception $e, $time)
        {
            $this->trait->addError($test, $e, $time);
        }

        public function addWarning(Test $test, Warning $e, $time)
        {
        }

        public function addFailure(Test $test, AssertionFailedError $e, $time)
        {
            $this->trait->addError($test, $e, $time);
        }

        public function addIncompleteTest(Test $test, \Exception $e, $time)
        {
        }

        public function addRiskyTest(Test $test, \Exception $e, $time)
        {
        }

        public function addSkippedTest(Test $test, \Exception $e, $time)
        {
        }

        public function endTestSuite(TestSuite $suite)
        {
        }

        public function startTest(Test $test)
        {
        }

        public function endTest(Test $test, $time)
        {
        }
=======
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class TestListener extends \PHPUnit_Framework_TestSuite implements \PHPUnit_Framework_TestListener
{
    public static $enabledPolyfills;
    private $suite;

    public function __construct(\PHPUnit_Framework_TestSuite $suite = null)
    {
        if ($suite) {
            $this->suite = $suite;
            $this->setName($suite->getName().' with polyfills enabled');
            $this->addTest($suite);
        }
    }

    public function startTestSuite(\PHPUnit_Framework_TestSuite $mainSuite)
    {
        if (null !== self::$enabledPolyfills) {
            return;
        }
        self::$enabledPolyfills = false;

        foreach ($mainSuite->tests() as $suite) {
            $testClass = $suite->getName();
            if (!$tests = $suite->tests()) {
                continue;
            }
            if (!preg_match('/^(.+)\\\\Tests(\\\\.*)Test$/', $testClass, $m)) {
                $mainSuite->addTest(self::warning('Unknown naming convention for '.$testClass));
                continue;
            }
            if (!class_exists($m[1].$m[2])) {
                continue;
            }
            $testedClass = new \ReflectionClass($m[1].$m[2]);
            $bootstrap = new \SplFileObject(dirname($testedClass->getFileName()).'/bootstrap.php');
            $warnings = array();
            $defLine = null;

            foreach (new \RegexIterator($bootstrap, '/return p\\\\'.$testedClass->getShortName().'::/') as $defLine) {
                if (!preg_match('/^\s*function (?P<name>[^\(]++)(?P<signature>\([^\)]*+\)) \{ (?<return>return p\\\\'.$testedClass->getShortName().'::[^\(]++)(?P<args>\([^\)]*+\)); \}$/', $defLine, $f)) {
                    $warnings[] = self::warning('Invalid line in bootstrap.php: '.trim($defLine));
                    continue;
                }
                $testNamespace = substr($testClass, 0, strrpos($testClass, '\\'));
                if (function_exists($testNamespace.'\\'.$f['name'])) {
                    continue;
                }

                try {
                    $r = new \ReflectionFunction($f['name']);
                    if ($r->isUserDefined()) {
                        throw new \ReflectionException();
                    }
                    if (false !== strpos($f['signature'], '&')) {
                        $defLine = sprintf('return \\%s%s', $f['name'], $f['args']);
                    } else {
                        $defLine = sprintf("return \\call_user_func_array('%s', func_get_args())", $f['name']);
                    }
                } catch (\ReflectionException $e) {
                    $defLine = sprintf("throw new \PHPUnit_Framework_SkippedTestError('Internal function not found: %s')", $f['name']);
                }

                eval(<<<EOPHP
namespace {$testNamespace};

use Symfony\Polyfill\Util\TestListener;
use {$testedClass->getNamespaceName()} as p;

function {$f['name']}{$f['signature']}
{
    if ('{$testClass}' === TestListener::\$enabledPolyfills) {
        {$f['return']}{$f['args']};
    }

    {$defLine};
}
EOPHP
                );
            }
            if (!$warnings && null === $defLine) {
                $warnings[] = new \PHPUnit_Framework_SkippedTestError('No Polyfills found in bootstrap.php for '.$testClass);
            } else {
                $mainSuite->addTest(new static($suite));
            }
        }
        foreach ($warnings as $w) {
            $mainSuite->addTest($w);
        }
    }

    protected function setUp()
    {
        self::$enabledPolyfills = $this->suite->getName();
    }

    protected function tearDown()
    {
        self::$enabledPolyfills = false;
    }

    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        if (false !== self::$enabledPolyfills) {
            $r = new \ReflectionProperty('Exception', 'message');
            $r->setAccessible(true);
            $r->setValue($e, 'Polyfills enabled, '.$r->getValue($e));
        }
    }

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->addError($test, $e, $time);
    }

    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    public function addRiskyTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
    }

    public function startTest(\PHPUnit_Framework_Test $test)
    {
    }

    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
>>>>>>> 0ea4546893e9acc54bc39e95182e7aba2dfa8f5d
    }
}
