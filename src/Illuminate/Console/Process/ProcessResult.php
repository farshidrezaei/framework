<?php

namespace Illuminate\Console\Process;

use Illuminate\Console\Contracts\ProcessResult as ProcessResultContract;
use Illuminate\Console\Process\Exceptions\ProcessFailedException;
use Symfony\Component\Process\Process;

class ProcessResult implements ProcessResultContract
{
    /**
     * The underlying process instance.
     *
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    /**
     * Create a new process result instance.
     *
     * @param  \Symfony\Component\Process\Process  $process
     * @return void
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * Get the original command executed by the process.
     *
     * @return string
     */
    public function command()
    {
        return $this->process->getCommandLine();
    }

    /**
     * Determine if the process was successful.
     *
     * @return bool
     */
    public function successful()
    {
        return $this->process->isSuccessful();
    }

    /**
     * Get the exit code of the process.
     *
     * @return int
     */
    public function exitCode()
    {
        return $this->process->getExitCode();
    }

    /**
     * Get the standard output of the process.
     *
     * @return string
     */
    public function output()
    {
        return $this->process->getOutput();
    }

    /**
     * Get the error output of the process.
     *
     * @return string
     */
    public function errorOutput()
    {
        return $this->process->getErrorOutput();
    }

    /**
     * Throw an exception if the process failed.
     *
     * @param  callable|null  $callback
     * @return $this
     */
    public function throw($callback = null)
    {
        if ($this->successful()) {
            return $this;
        }

        $exception = new ProcessFailedException($this);

        if ($callback) {
            $callback($this, $exception);
        }

        throw $exception;
    }

    /**
     * Throw an exception if the process failed and the given condition is true.
     *
     * @param  bool  $condition
     * @param  callable|null  $callback
     * @return $this
     */
    public function throwIf($condition, $callback = null)
    {
        if ($condition) {
            return $this->throw($callback);
        }

        return $this;
    }
}
