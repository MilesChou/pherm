<?php

namespace MilesChou\Pherm\IO;

use function get_resource_type;
use InvalidArgumentException;
use function is_resource;
use function stream_get_meta_data;
use function strpos;

class ResourceOutputStream implements OutputStream
{
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

    public function isInteractive(): bool
    {
        return posix_isatty($this->stream);
    }

    public function write(string $buffer): void
    {
        fwrite($this->stream, $buffer);
    }
}
