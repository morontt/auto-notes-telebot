<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT! (protoc-gen-twirp_php 0.14.0)

declare(strict_types=1);

namespace AutoNotes\Auth;

use Twirp\ErrorCode;
use Twirp\Error;

/**
 * Error class implementation for Twirp errors.
 */
final class TwirpError extends \Exception implements Error
{
    /**
     * @var string
     */
    private $errorCode;

    /**
     * @var array
     */
    private $meta = [];

    public function __construct(string $code, string $message, int $exCode = 0, ?\Throwable $previous = null)
    {
        $this->errorCode = $code;

        parent::__construct($message, $exCode, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setMeta(string $key, string $value): void
    {
        $this->meta[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta(string $key): string
    {
        if (isset($this->meta[$key])) {
            return $this->meta[$key];
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaMap(): array
    {
        return $this->meta;
    }

    /**
     * Generic constructor for a TwirpError. The error code must be
     * one of the valid predefined constants, otherwise it will be converted to an
     * error {type: Internal, msg: "invalid error type {code}"}. If you need to
     * add metadata, use setMeta(key, value) method after building the error.
     */
    public static function newError(string $code, string $msg, ?\Throwable $previous = null): self
    {
        if (ErrorCode::isValid($code)) {
            return new self($code, $msg, 0, $previous);
        }

        return new self(ErrorCode::Internal, 'invalid error type '.$code, 0, $previous);
    }

    /**
     * Wrap a throwable. It adds the
     * underlying error's type as metadata with a key of "cause", which can be
     * useful for debugging. Should be used in the common case of an unexpected
     * error returned from another API, but sometimes it is better to build a more
     * specific error (like with self::newError(self::Unknown, $e->getMessage()), for example).
     */
    public static function errorFrom(\Throwable $e, string $msg = ''): self
    {
        $msg = empty($msg) ? $e->getMessage() : $msg;
        // Exception->getCode is not guaranteed to return an int, see https://www.php.net/manual/en/exception.getcode.php
        $code = is_int($e->getCode()) ? $e->getCode() : 0;

        $err = new self(ErrorCode::Internal, $msg, $code, $e);
        $err->setMeta('cause', $e->getMessage());

        return $err;
    }
}
