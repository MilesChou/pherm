<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\SizeAwareTrait;
use MilesChou\Pherm\Contracts\TTY as TTYContract;
use RuntimeException;

class TTY implements TTYContract
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
     * @inheritDoc
     */
    public function disableCanonicalMode(): TTYContract
    {
        $this->exec('-icanon');
        $this->isCanonicalMode = false;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function disableEchoBack(): TTYContract
    {
        $this->exec('-echo');
        $this->isEchoBack = false;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function enableCanonicalMode(): TTYContract
    {
        $this->exec('icanon');
        $this->isCanonicalMode = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function enableEchoBack(): TTYContract
    {
        $this->exec('echo');
        $this->isEchoBack = true;

        return $this;
    }

    public function height(): int
    {
        if ($this->height === null) {
            $this->reloadSize();
        }

        return $this->height;
    }

    public function isCanonicalMode(): bool
    {
        if (null === $this->isCanonicalMode) {
            $this->parseAll();
        }

        return $this->isCanonicalMode;
    }

    public function isEchoBack(): bool
    {
        if (null === $this->isEchoBack) {
            $this->parseAll();
        }

        return $this->isEchoBack;
    }

    /**
     * @return array [$width, $height]
     */
    public function reloadSize(): array
    {
        // [$rows, $columns]
        $size = explode(' ', exec('stty size'));

        $this->setSize((int)$size[1], (int)$size[0]);

        return $this->getSize();
    }

    public function restore(): void
    {
        $this->exec($this->originalConfiguration);
    }

    public function store(): void
    {
        $this->originalConfiguration = $this->exec('-g');
    }

    public function width(): int
    {
        if ($this->width === null) {
            $this->reloadSize();
        }

        return $this->width;
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
