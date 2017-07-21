<?php
/**
 * Collection of validators for common cases
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Ilya Serdyuk <ilya@serdyuk.pro>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * Валидатор ОГРН (основной государственный регистрационный номер),
 * применяемого для идентификации юридических лиц в Российской Федерации
 *
 * @see https://ru.wikipedia.org/wiki/Основной_государственный_регистрационный_номер
 */
class ZFValidators_Validate_Russia_OGRN extends Zend_Validate_Abstract
{

    const INVALID = 'ruInnInvalid';

    protected $_messageTemplates = array(
        self::INVALID => "'%value%' is not a INN"
    );

    /**
     * Это корректный ОГРН?
     *
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);


        if ($this->isCompany($value)) {
            return true;
        }

        if ($this->isPerson($value)) {
            return true;
        }

        return false;
    }

    /**
     * Это ОГРН юридического лица?
     *
     * @param string $value
     * @return boolean
     */
    public function isCompany($value)
    {
        $value = (string) $value;

        if (1 != preg_match('/^[0-9]{13}$/', $value)) {
            return false;
        }

        $c = floor(substr($value, 0, 11) % 11) % 10;
        if ($c != intval($value[12])) {
            return false;
        }

        return true;
    }

    /**
     * Это ОГРН индивидуального предпринимателя?
     *
     * @param string $value
     * @return boolean
     */
    public function isPerson($value)
    {
        $value = (string) $value;

        if (1 != preg_match('/^[0-9]{15}$/', $value)) {
            return false;
        }

        $c = floor(substr($value, 0, 13) % 13) % 10;
        if ($c != intval($value[14])) {
            return false;
        }

        return true;
    }

}
