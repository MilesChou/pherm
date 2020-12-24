<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\SizeAwareTrait;
use RuntimeException;

class TTY
{
    use SizeAwareTrait;

    /**
     * @var bool
     */
    private $isCanonicalMode;

    /**
     * @var bool
     */
    private $isEchoBack;

    /**
     * @var string
     */
    private $originalConfiguration;

    /**
     * Disable canonical input (allow each key press for reading, rather than the whole line)
     *
     * @return static
     * @see https://www.gnu.org/software/libc/manual/html_node/Canonical-or-Not.html
     */
    public function disableCanonicalMode(): TTY
    {
        $this->exec('-icanon');
        $this->isCanonicalMode = false;

        return $this;
    }

    /**
     * Disables echoing every character back to the terminal.
     *
     * This means we do not have to clear the line when reading.
     *
     * @return static
     */
    public function disableEchoBack(): TTY
    {
        $this->exec('-echo');
        $this->isEchoBack = false;

        return $this;
    }

    /**
     * Enable canonical input - read input by line
     *
     * @return static
     * @see https://www.gnu.org/software/libc/manual/html_node/Canonical-or-Not.html
     */
    public function enableCanonicalMode(): TTY
    {
        $this->exec('icanon');
        $this->isCanonicalMode = true;

        return $this;
    }

    /**
     * Enable echoing back every character input to the terminal.
     *
     * @return static
     */
    public function enableEchoBack(): TTY
    {
        $this->exec('echo');
        $this->isEchoBack = true;

        return $this;
    }

    /**
     * Is canonical mode enabled or not
     */
    public function isCanonicalMode(): bool
    {
        if (null === $this->isCanonicalMode) {
            $this->parseAll();
        }

        return $this->isCanonicalMode;
    }

    /**
     * Is echo back mode enabled
     */
    public function isEchoBack(): bool
    {
        if (null === $this->isEchoBack) {
            $this->parseAll();
        }

        return $this->isEchoBack;
    }

    /**
     * @return array<int> [$width, $height]
     */
    public function reloadSize(): array
    {
        $term = new \Symfony\Component\Console\Terminal();

        $this->setSize($term->getWidth(), $term->getHeight());

        return $this->size();
    }

    public function restore(): void
    {
        $this->exec($this->originalConfiguration);
    }

    public function store(): void
    {
        $this->originalConfiguration = $this->exec('-g');
    }

    private function parseAll(): void
    {
        $output = $this->exec('-a');

        $this->isEchoBack = (bool)preg_match('/[^-]echo/', $output);
        $this->isCanonicalMode = (bool)preg_match('/[^-]icanon/', $output);
    }

    /**
     * @param string $parameter
     * @return string
     */
    private function exec($parameter = ''): string
    {
        if (!function_exists('proc_open')) {
            throw new RuntimeException("Expect 'proc_open' function");
        }

        $descriptors = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open(
            'stty ' . $parameter,
            $descriptors,
            $pipes,
            null,
            null,
            ['suppress_errors' => true]
        );

        if (!is_resource($process)) {
            throw new RuntimeException("Execute 'stty -a' error");
        }

        $info = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        return (string)$info;
    }
}
