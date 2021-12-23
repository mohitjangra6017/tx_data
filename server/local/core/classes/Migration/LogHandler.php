<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Migration;

use moodle_exception;
use Throwable;

final class LogHandler
{
    /**
     * @var resource
     */
    private $logFile;

    private string $logPath;

    private bool $stdout = false;

    private bool $isCapturingStdOut = false;

    private array $sections = [];

    private ?string $tempSection = null;

    public function __construct()
    {
        global $CFG;

        $path = $CFG->dataroot . '/migrations/logs';
        make_writable_directory($path);

        // Protect against this being run multiple times per second. Shouldn't ever be an issue, but worth protecting against it.
        $date = date('Y-m-d-H-i-s');
        do {
            $this->logPath = $path . '/migration-' . $date . '.log';
        } while (is_file($this->logPath));

        $this->logFile = fopen($this->logPath, 'w+');
    }

    public function finish(): void
    {
        if (is_resource($this->logFile)) {
            fclose($this->logFile);
        }
    }

    /**
     * @param string $message
     * @param array $context Any extra information to add to the log message. Appended as a JSON encoded string.
     * @return $this
     */
    public function log(string $message, array $context = []): self
    {
        $string = $this->formatLog($message, $context);
        fwrite($this->logFile, $string . PHP_EOL, mb_strlen($string . PHP_EOL));

        if ($this->stdout) {
            mtrace($string);
        }

        return $this;
    }

    /**
     * Specific logging for Throwables and Exceptions.
     * @param Throwable $throwable
     * @param string $description
     */
    public function logThrowable(Throwable $throwable, string $description = ''): void
    {
        $string = $description . ': A ' . get_class($throwable) . ' has been encountered.';
        // Do a basic error message for the front end, we'll put all the details in the log file.
        if ($this->stdout) {
            $this->setSection('SYSTEM');
            mtrace($this->formatLog($string . " See the log file ({$this->logPath}) for more details."));
        }

        if ($throwable instanceof moodle_exception) {
            $string .= PHP_EOL . $throwable->debuginfo;
        }

        $this->setSection('SYSTEM');
        $string = $this->formatLog($string. PHP_EOL . $throwable->getMessage() . PHP_EOL . $throwable->getTraceAsString() . PHP_EOL);
        fwrite($this->logFile, $string, mb_strlen($string));
    }

    /**
     * Starts a prefixed section of logging. Causes all logs after this to be prefixed with the section name.
     * If called while another section is open, will nest your section inside the other. Closing the outer section closes your section as well.
     * @param string $section
     * @return $this
     */
    public function startSection(string $section): self
    {
        $this->sections[] = $section;
        return $this;
    }

    /**
     * Restarts the sections, starting with the given section.
     * @param string $section
     * @return $this
     */
    public function restartSection(string $section): self
    {
        $this->sections = [$section];
        return $this;
    }

    /**
     * Stops the currently open section if $section is null.
     * If $section is non-null, closes that section and all others opened after it.
     * @param string|null $section
     * @return $this
     */
    public function stopSection(?string $section = null): self
    {
        if (is_null($section)) {
            array_pop($this->sections);
            return $this;
        }

        if (array_search($section, $this->sections) === false) {
            return $this;
        }

        do {
            $popped = array_pop($this->sections);
        }
        while ($popped !== $section);
        return $this;
    }

    /**
     * Sets the section for the next log message only.
     * @param string $section
     * @return $this
     */
    public function setSection(string $section): self
    {
        $this->tempSection = $section;
        return $this;
    }

    /**
     * Set this to true to also have the logs output to the CLI's standard output.
     * Logs will always be written to the log file, regardless of this setting.
     * @param bool $output
     */
    public function outputToStdOut(bool $output): void
    {
        $this->stdout = $output;
        if ($output) {
            mtrace("Log file created at {$this->logPath}. All logs will be available there after execution finished.");
        }
    }

    /**
     * Used to capture any output that is sent outside this class, such as _mtrace_.
     */
    public function startCapturingStdOut(): void
    {
        ob_start();
        ob_implicit_flush(false);
        $this->isCapturingStdOut = true;
    }

    /**
     * Stops capturing any output started in _startCapturingStdOut_.
     */
    public function stopCapturingStdOut(): void
    {
        if (!$this->isCapturingStdOut) {
            return;
        }
        $this->isCapturingStdOut = false;
        $content = ob_get_clean();
        ob_implicit_flush(true);
        $this->log('Captured output from outside the Migration System:' . PHP_EOL . $content);
    }

    private function formatLog(string $message, array $context = []): string
    {
        if (!empty($context)) {
            try {
                $extra = json_encode($context, JSON_THROW_ON_ERROR);
            } catch (Throwable $e) {
                $extra = '';
            }
        } else {
            $extra = '';
        }

        $section = $this->tempSection ?? end($this->sections) ?? '';
        if ($this->tempSection) {
            $this->tempSection = null;
        }

        return sprintf('[%s] [%s] %s %s', date(DATE_ISO8601), $section, $message, $extra);
    }

    public function __destruct()
    {
        if (!is_resource($this->logFile)) {
            return;
        }

        if ($this->isCapturingStdOut) {
            $this->stopCapturingStdOut();
        }

        $this->finish();
    }
}