<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use App\Infrastructure\Exception\InvalidRequestArgumentException;
use DateTime;

class Date
{
    public const FORMAT = \DATE_ATOM;

    public function __construct(protected DateTime $value)
    {
    }

    public static function fromDate(DateTime $date) : self
    {
        return new static($date);
    }

    public static function fromString(string $value) : self
    {
        $date = DateTime::createFromFormat(self::FORMAT, $value);

        if ($date === false) {
            throw new InvalidRequestArgumentException(sprintf('Invalid date, must be in format "%s"', self::FORMAT));
        }

        return self::fromDate($date);
    }

    public function equals(self $other) : bool
    {
        return $this->value->getTimestamp() === $other->value->getTimestamp();
    }

    public function value() : DateTime
    {
        return $this->value;
    }

    public function __toString() : string
    {
        return $this->value->format(\DATE_ATOM);
    }
}
