<?php

namespace MilesChou\Pherm\Output;

use InvalidArgumentException;
use MilesChou\Pherm\Concerns\InstantOutputTrait;
use MilesChou\Pherm\Contracts\Output;
use function get_resource_type;
use function is_resource;
use function stream_get_meta_data;
use function strpos;

class OutputStream implements Output
{
    use InstantOutputTrait;

    /**
     * @var string
     */
    private $buffer = '';

    /**
     * @var resource
     */
    private $stream;

    public function __construct($stream = STDOUT)
    {
        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new InvalidArgumentException('Expected a valid stream');
        }

        $meta = stream_get_meta_data($stream);
        if (strpos($meta['mode'], 'r') !== false && strpos($meta['mode'], '+') === false) {
            throw new InvalidArgumentException('Expected a writable stream');
        }

        $this->stream = $stream;
    }

    public function flush(): void
    {
        fwrite($this->stream, $this->buffer);

        $this->buffer = '';
    }

    public function isInteractive(): bool
    {
        return posix_isatty($this->stream);
    }

    public function write(string $buffer): void
    {
        $this->buffer .= $buffer;

        if ($this->isInstantOutput()) {
            $this->flush();
        }
    }
}
