<?php namespace Telenok\Core\Field\Decimal;

/*
 * Copyright (c)
 * Kirill chEbba Chebunin <iam@chebba.org>
 * Vladislav Veselinov <vladislav@v3labs.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 * 
 * https://github.com/v3labs/math
 * 
 * 
 * Immutable Arbitrary Precision decimal number.
 * Wrapper for BC Math
 *
 * 
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @author Vladislav Veselinov <vladislav@v3labs.com>
 * 
 * 
 * @class Telenok.Core.Field.Decimal.BigDecimal
 * Class to manipulate big numbers.
 */
class BigDecimal {

    const MAX_SCALE = 2147483647;
    const ROUND_UP = 1;
    const ROUND_DOWN = 2;
    const ROUND_CEILING = 3;
    const ROUND_FLOOR = 4;
    const ROUND_HALF_UP = 5;
    const ROUND_HALF_DOWN = 6;
    const ROUND_HALF_EVEN = 7;
    const ROUND_HALF_ODD = 8;
    const ROUND_UNNECESSARY = 9;
    const STRING_FORMAT_REGEX = '/^([-+])?([0-9]+)(\.([0-9]+))?(E([+-]?[0-9]+))?$/';

    /**
     * @private
     * @property {mixed} $value
     * Value of number.
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    private $value;

    /**
     * @private
     * @property {scalar} $scale
     * Value of dimension.
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    private $scale;

    /**
     * @constructor
     * Filling internal variables and validate dimension.
     * 
     * @param {mixed} $value
     * Value of number.
     * @param {scalar} $scale
     * Value of dimension.
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function __construct($value, $scale = null)
    {
        if ($scale !== null)
        {
            $scale = (int) $scale;

            if (abs($scale) > self::MAX_SCALE)
            {
                throw new \InvalidArgumentException(sprintf('Scale "%s" is grater than max "%s"', $scale, self::MAX_SCALE));
            }
        }

        if (!is_scalar($value))
        {
            throw new \InvalidArgumentException(sprintf('Value of type "%s" is not as scalar', gettype($value)));
        }

        $value = (string) $value;
        $matches = [];

        if (!preg_match(self::STRING_FORMAT_REGEX, $value, $matches))
        {
            throw new \InvalidArgumentException(sprintf('Wrong value "%s" format: expected "%s"', $value, self::STRING_FORMAT_REGEX));
        }

        $sign = $matches[1] === '-' ? '-' : '';
        $integer = ltrim($matches[2], '0') ? : '0';
        $fraction = isset($matches[4]) ? $matches[4] : '';
        $exponent = - strlen($fraction) + (isset($matches[6]) ? (int) $matches[6] : 0);

        $significand = $sign . $integer . $fraction;

        $exponentScale = abs(min($exponent, 0));

        $newValue = bcmul($significand, bcpow(10, $exponent, $exponentScale), $exponentScale);

        list($integer, $fraction) = (array_pad(explode('.', $newValue, 2), 2, ''));

        if ($scale === null)
        {
            $scale = strlen($fraction);
        } 
        else
        {
            $scale = (int) $scale;
            
            if (strlen($fraction) > $scale)
            {
                $fraction = substr($fraction, 0, $scale);
            } 
            else
            {
                $fraction = str_pad($fraction, $scale, '0');
            }
        }

        $this->value = $integer . ($scale ? ('.' . $fraction) : '');
        $this->scale = $scale;
    }

    /**
     * @method create
     * Create new instance of Telenok.Core.Field.Decimal.BigDecimal.
     * @static
     * @param {scalar} $value
     * Value of number.
     * @param {scalar} $scale
     * Value of dimension.
     * @return {Telenok.Core.Field.Decimal.BigDecimal}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public static function create($value, $scale = null)
    {
        return new static($value, $scale);
    }

    /**
     * @method zero
     * Create new instance of Telenok.Core.Field.Decimal.BigDecimal.
     * @static
     * @return {Telenok.Core.Field.Decimal.BigDecimal}
     * Return instance with zero value and zero scale
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public static function zero()
    {
        return new static(0, 0);
    }

    /**
     * @method one
     * Create new instance of Telenok.Core.Field.Decimal.BigDecimal.
     * @static
     * @return {Telenok.Core.Field.Decimal.BigDecimal}
     * Return instance with value equal one and zero scale
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public static function one()
    {
        return new static(1, 0);
    }

    /**
     * @method value
     * Return value of instance.
     * @return {Number}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @method scale
     * Return scale of instance.
     * @return {Integer}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function scale()
    {
        return $this->scale;
    }

    /**
     * @method setScale
     * Create new instance with the same value and new scale.
     * @param {scalar} $scale
     * Value of dimension.
     * @return {Telenok.Core.Field.Decimal.BigDecimal}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function setScale($scale)
    {
        return new static($this->value(), $scale);
    }

    /**
     * @method precision
     * Return amount of digits in value.
     * @return {Integer}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function precision()
    {
        $parts = explode('.', $this->value);

        return strlen(ltrim($parts[0], '-'));
    }

    /**
     * @method __toString
     * Convert value to string.
     * @return {String}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function __toString()
    {
        return $this->value();
    }

    /**
     * @method add
     * Calculate sum of two Telenok.Core.Field.Decimal.BigDecimal and return new 
     * instance.
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $addend
     * Summable number.
     * @return {Telenok.Core.Field.Decimal.BigDecimal}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function add(BigDecimal $addend)
    {
        $scale = max($this->scale(), $addend->scale());
        
        return new static(bcadd($this->value, $addend->value(), $scale), $scale);
    }

    /**
     * @method subtract
     * Calculate subtract of two Telenok.Core.Field.Decimal.BigDecimal and return new 
     * instance.
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $subtrahend
     * Subtract number.
     * @return {Telenok.Core.Field.Decimal.BigDecimal}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function subtract(BigDecimal $subtrahend)
    {
        $scale = max($this->scale(), $subtrahend->scale());
        
        return new static(bcsub($this->value, $subtrahend->value(), $scale), $scale);
    }

    /**
     * @method multiply
     * Calculate multiply of two Telenok.Core.Field.Decimal.BigDecimal and return new 
     * instance.
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $multiplier
     * To multiply.
     * @return {Telenok.Core.Field.Decimal.BigDecimal}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function multiply(BigDecimal $multiplier)
    {
        $scale = min($this->scale + $multiplier->scale(), self::MAX_SCALE);

        return new static(bcmul($this->value, $multiplier->value(), $scale), $scale);
    }

    /**
     * @method divide
     * Calculate divide of two Telenok.Core.Field.Decimal.BigDecimal and return new 
     * instance.
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $divisor
     * To divide.
     * @return {Telenok.Core.Field.Decimal.BigDecimal}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function divide(BigDecimal $divisor)
    {
        if ($divisor->signum() === 0)
        {
            throw new \InvalidArgumentException('Division by zero');
        }
        
        $scale = min($this->scale + $divisor->scale(), self::MAX_SCALE);

        return new static(bcdiv($this->value, $divisor->value(), $scale), $scale);
    }

    /**
     * @method pow
     * Calculate pow return new instance.
     * @param {Integer} $divisor
     * To pow.
     * @return {Telenok.Core.Field.Decimal.BigDecimal}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function pow($n)
    {
        $n = (int) $n;
        
        if ($n < 0)
        {
            throw new \InvalidArgumentException(sprintf('Power "%s" is negative', $n));
        }
        
        if ($n === 0)
        {
            return static::one();
        }

        return new static(bcpow($this->value, $n, self::MAX_SCALE));
    }

    /**
     * @method signum
     * Returns the number sign.
     * 
     * @return {Integer}
     * Return [-1, 0, 1].
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function signum()
    {
        return $this->compareTo(self::zero());
    }

    /**
     * @method negate
     * Returns negative.
     * 
     * @return {Telenok.Core.Field.Decimal.BigDecimal}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function negate()
    {
        $value = $this->value;
        
        switch ($this->signum())
        {
            case -1:
                $value = substr($value, 1);
                break;
            
            case 1:
                $value = '-' . $value;
                break;
        }

        return new static($value, $this->scale);
    }

    /**
     * @method abs
     * Returns absolute value.
     * 
     * @return {Telenok.Core.Field.Decimal.BigDecimal}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function abs()
    {
        return $this->signum() < 0 ? $this->negate() : new static($this->value, $this->scale);
    }

    /**
     * @method round
     * Returns round value.
     * 
     * @param {Integer} $scale
     * @param {Integer} $roundMode
     *
     * @return static
     * @throws \RuntimeException 
     * If round mode is UNNECESSARY and digit truncation is required
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function round($scale = 0, $roundMode = self::ROUND_HALF_UP)
    {
        if ($scale >= $this->scale)
        {
            return new static($this->value, $scale);
        }

        // Break string to 2 parts. Ex '123.45678', 3: '123.456' and '78'
        list($newValue, $truncated) = str_split($this->value, strlen($this->value) - ($this->scale - $scale));
        
        // Remove trailing dot for integer round
        if ($scale === 0)
        {
            $newValue = substr($newValue, 0, -1);
        }
        // remove extra zeros
        $truncated = rtrim($truncated, '0');

        // Check if truncated digits are zeros, than no rounding required
        if ($truncated === '')
        {
            return new static($newValue, $scale);
        }

        // If we should not round but got some truncated digits
        if ($roundMode === self::ROUND_UNNECESSARY)
        {
            throw new \RuntimeException(sprintf('Digits "%s" of "%s" should not be truncated with scale "%d"', $truncated, $this->value, $scale));
        }

        $rounded = new static($newValue, $scale);

        $sign = $this->signum() !== -1;
        
        if (self::isRoundAdditionRequired($roundMode, $sign, $newValue, $truncated))
        {
            // If addition required we add (+/-)1E-{scale}
            $addition = ($sign ? '' : '-') . '1e-' . $scale;
            $rounded = $rounded->add(new static(number_format($addition, $scale, '.', '')));
        }

        return $rounded;
    }

    /**
     * @private
     * @method isRoundAdditionRequired
     * Validate requiring addditional round value.
     * 
     * @param {Integer} $roundMode
     * @param {Integer} $sign
     * @param {String} $value
     * @param {Boolean} $truncated
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    private static function isRoundAdditionRequired($roundMode, $sign, $value, $truncated)
    {
        switch ($roundMode)
        {
            case self::ROUND_UP:
                return true;

            case self::ROUND_DOWN:
                return false;

            case self::ROUND_CEILING:
                return $sign;

            case self::ROUND_FLOOR:
                return !$sign;

            case self::ROUND_HALF_UP:
                return $truncated === '5' || $truncated[0] >= 5;

            case self::ROUND_HALF_DOWN:
                return !($truncated === '5' || $truncated[0] < 5 );

            case self::ROUND_HALF_EVEN:
                return !($truncated[0] < 5 || ($truncated === '5' && ($value[strlen($value) - 1] % 2 === 0)));

            case self::ROUND_HALF_ODD:
                return !($truncated[0] < 5 || $truncated === '5' && ($value[strlen($value) - 1] % 2 === 1));
        }

        return false;
    }

    /**
     * @method compareTo
     * Compare with other BigDecimal number.
     * 
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $number
     *
     * @return {Integer}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function compareTo(BigDecimal $number)
    {
        $scale = max($this->scale(), $number->scale());
        
        return bccomp($this->value, $number->value(), $scale);
    }

    /**
     * @method isEqualTo
     * Compare with other BigDecimal number and return TRUE if equal.
     * 
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $number
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function isEqualTo(BigDecimal $number)
    {
        return $this->compareTo($number) == 0;
    }

    /**
     * @method isGreaterThan
     * Compare with other BigDecimal number and return TRUE if greater.
     * 
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $number
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function isGreaterThan(BigDecimal $number)
    {
        return $this->compareTo($number) == 1;
    }

    /**
     * @method isGreaterThanOrEqualTo
     * Compare with other BigDecimal number and return TRUE if greater or equal.
     * 
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $number
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function isGreaterThanOrEqualTo(BigDecimal $number)
    {
        return $this->compareTo($number) >= 0;
    }

    /**
     * @method isLessThan
     * Compare with other BigDecimal number and return TRUE if less.
     * 
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $number
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function isLessThan(BigDecimal $number)
    {
        return $this->compareTo($number) == -1;
    }

    /**
     * @method isLessThanOrEqualTo
     * Compare with other BigDecimal number and return TRUE if less or equal.
     * 
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $number
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function isLessThanOrEqualTo(BigDecimal $number)
    {
        return $this->compareTo($number) <= 0;
    }

    /**
     * @method isNegative
     * Compare with zero and return TRUE if less.
     * 
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $number
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function isNegative()
    {
        return $this->isLessThan(static::zero());
    }

    /**
     * @method isPositive
     * Compare with zero and return TRUE if more.
     * 
     * @param {Telenok.Core.Field.Decimal.BigDecimal} $number
     *
     * @return {Boolean}
     * @member Telenok.Core.Field.Decimal.BigDecimal
     */
    public function isPositive()
    {
        return $this->isGreaterThan(static::zero());
    }
}