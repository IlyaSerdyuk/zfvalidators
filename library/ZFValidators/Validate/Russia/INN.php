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
 * Валидатор ИНН (идентификационный номер налогоплательщика),
 * применяемого в налоговой системе Российской Федерации
 *
 * ВНИМАНИЕ! Валидатор проверят только формальную корректность ИНН.
 * Для проверки существования ИНН необходимо воспользоваться государственными сервисом.
 *
 * @see https://ru.wikipedia.org/wiki/Идентификационный_номер_налогоплательщика
 */
class ZFValidators_Validate_Russia_INN extends Zend_Validate_Abstract
{

    const INVALID = 'ruInnInvalid';

    protected $_messageTemplates = array(
        self::INVALID => "'%value%' is not a INN"
    );

    /**
     * Это корректный ИНН?
     *
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);


        if ($this->isPerson($value)) {
            return true;
        }

        if ($this->isCompany($value)) {
            return true;
        }

        return false;
    }

    /**
     * Это ИНН физического лица или индивидуального предпринимателя?
     *
     * @param string $value
     * @return boolean
     */
    public function isPerson($value)
    {
        $value = (string) $value;

        if (1 != preg_match('/^[0-9]{12}$/', $value)) {
            return false;
        }

        // Проверка первой контрольной цифры
        $c1 = $value[0] * 7
            + $value[1] * 2
            + $value[2] * 4
            + $value[3] * 10
            + $value[4] * 3
            + $value[5] * 5
            + $value[6] * 9
            + $value[7] * 4
            + $value[8] * 6
            + $value[9] * 8;
        if (($c1 % 11) % 10 != intval($value[10])) {
            return false;
        }

        // Проверка второй контрольной цифры
        $c2 = $value[0] * 3
            + $value[1] * 7
            + $value[2] * 2
            + $value[3] * 4
            + $value[4] * 10
            + $value[5] * 3
            + $value[6] * 5
            + $value[7] * 9
            + $value[8] * 4
            + $value[9] * 6
            + $value[10] * 8;
        if (($c2 % 11) % 10 != intval($value[11])) {
            return false;
        }

        return true;
    }

    /**
     * Это ИНН юридического лица?
     *
     * @param string $value
     * @return boolean
     */
    public function isCompany($value)
    {
        $value = (string) $value;

        if (1 != preg_match('/^[0-9]{10}$/', $value)) {
            return false;
        }

        // Проверка контрольной цифры
        $c1 = $value[0] * 2
            + $value[1] * 4
            + $value[2] * 10
            + $value[3] * 3
            + $value[4] * 5
            + $value[5] * 9
            + $value[6] * 4
            + $value[7] * 6
            + $value[8] * 8;
        if (($c1 % 11) % 10 != intval($value[9])) {
            return false;
        }

        return true;
    }

}
