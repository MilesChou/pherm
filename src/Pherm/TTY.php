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
    private $isCanonical;

    /**
     * @var bool
     */
    private $isEchoBack;

    /**
     * @var string
     */
    private $originalConfiguration;

    /**
     * @param string $parameter
     * @return string
     */
    public function exec($parameter = ''): string
    {
        if (!function_exists('proc_open')) {
            throw new RuntimeException("Expect 'proc_open' function");
        }

        $descriptorspec = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open(
            'stty ' . $parameter,
            $descriptorspec,
            $pipes,
            null,
            null,
            ['suppress_errors' => true]
        );

        if (is_resource($process)) {
            $info = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);

            return (string)$info;
        } else {
            throw new RuntimeException("Execute 'stty -a' error");
        }
    }

    public function disableCanonicalMode(): TTYContract
    {
        $this->exec('-icanon');
        $this->isCanonical = false;

        return $this;
    }

    public function disableEchoBack(): TTYContract
    {
        $this->exec('-echo');
        $this->isEchoBack = false;

        return $this;
    }

    public function enableCanonicalMode(): TTYContract
    {
        $this->exec('icanon');
        $this->isCanonical = true;

        return $this;
    }

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
        return $this->isCanonical;
    }

    public function isEchoBack(): bool
    {
        return $this->isEchoBack;
    }

    /**
     * @return array
     */
    public function parseAll(): array
    {
        $output = $this->exec('-a');

        return [
            'echo' => (bool)preg_match('/[^-]echo/', $output),
            'icanon' => (bool)preg_match('/[^-]icanon/', $output),
        ];
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
}
