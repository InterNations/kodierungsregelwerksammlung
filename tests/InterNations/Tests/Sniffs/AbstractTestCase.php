<?php
class InterNations_Tests_Sniffs_AbstractTestCase extends PHPUnit_Framework_TestCase
{
    protected static function analyze(array $sniffs, $files)
    {
        $sniffs = array_map(
            static function ($sniff) {
                $sniff = __DIR__ . '/../../../../src/' . $sniff . '.php';
                static::assertFileExists($sniff, 'Sniff does not exist');
                return $sniff;
            },
            $sniffs
        );
        $files = array_map(
            static function ($file) {
                static::assertFileExists($file, 'Source file does not exists');
                return $file;
            },
            (array) $files
        );

        $codeSniffer = new PHP_CodeSniffer();
        $codeSniffer->registerSniffs($sniffs, []);
        $codeSniffer->cli->setCommandLineValues(['--showSources=false', '--report=checkstyle']);
        $codeSniffer->populateTokenListeners();

        $report = [];
        foreach ($files as $file) {
            $phpcsFile = $codeSniffer->processFile($file);

            $report[$file] = [];
            $report[$file]['numWarnings'] = $phpcsFile->getWarningCount();
            $report[$file]['warnings'] = $phpcsFile->getWarnings();

            $report[$file]['numErrors'] = $phpcsFile->getErrorCount();
            $report[$file]['errors'] = $phpcsFile->getErrors();
        }

        return $report;
    }

    protected static function assertReportCount($errorCount, $warningsCount, array $errors, $file)
    {
        static::assertArrayHasKey(
            $file,
            $errors,
            static::createErrorMessage($errors, 'No report for file "%s"', $file)
        );

        static::assertSame(
            $errorCount,
            $errors[$file]['numErrors'],
            static::createErrorMessage($errors, sprintf('Error count does not match. Expected %d', $errorCount))
        );
        static::assertSame(
            $warningsCount,
            $errors[$file]['numWarnings'],
            static::createErrorMessage($errors, 'Warning count does not match')
        );
    }

    protected static function assertReportContains(array $errors, $file, $level, $message, $source = null, $severity = null)
    {
        static::assertArrayHasKey(
            $file,
            $errors,
            static::createErrorMessage($errors, 'No report for file "%s"', $file)
        );
        static::assertArrayHasKey(
            $level,
            $errors[$file],
            static::createErrorMessage($errors, 'No report found for file "%s" and level "%s"', $file, $level)
        );

        $found = false;

        $flattenedError = static::flattenErrors($errors[$file][$level]);
        foreach ($flattenedError as $error) {
            if ($error['message'] === $message) {
                $found = true;
                break;
            }
        }

        static::assertTrue(
            $found,
            static::createErrorMessage($errors, 'Error message "%s" not found', $message)
        );
        if ($severity !== null) {
            static::assertSame(
                $severity,
                $error['severity'],
                static::createErrorMessage($errors, 'Severity does not match')
            );
        }
        if ($source !== null) {
            static::assertSame(
                $source,
                $error['source'],
                static::createErrorMessage($errors, 'Source does not match')
            );
        }
    }

    protected static function flattenErrors(array $errors)
    {
        $flattened = [];

        foreach ($errors as $errorList) {
            foreach ($errorList as $nestedErrorList) {
                foreach ($nestedErrorList as $error) {
                    $flattened[] = $error;
                }
            }
        }

        return $flattened;
    }

    protected static function createErrorMessage(array $errors, $message)
    {
        $args = func_get_args();
        $errors = array_shift($args);
        $message = array_shift($args);

        return vsprintf($message, $args) . "\n" . print_r($errors, true);
    }
}
