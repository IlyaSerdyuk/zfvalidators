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
 * Валидатор ОКПО (общероссийский классификатор предприятий и организаций),
 * применяемого в статистической системе Российской Федерации
 *
 * @see https://ru.wikipedia.org/wiki/Общероссийский_классификатор_предприятий_и_организаций
 */
class ZFValidators_Validate_Russia_OKPO extends Zend_Validate_Abstract
{

    const INVALID = 'ruOkpoInvalid';

    protected $_messageTemplates = array(
        self::INVALID => "'%value%' is not a OKPO"
    );

    /**
     * Это корректный ОКПО?
     *
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);


        $value = (string) $value;

        if (1 != preg_match('/^[0-9]{8,10}$/', $value)) {
            return false;
        }

        // Номер символа контрольной цифры
        $ci = strlen($value) == 8 ? 7 : 9;

        $cn = $value[0] * 1
            + $value[1] * 2
            + $value[2] * 3
            + $value[3] * 4
            + $value[4] * 5
            + $value[5] * 6
            + $value[6] * 7;

        if (9 == $ci) {
            $cn += $value[7] * 8
                 + $value[8] * 9;
        }

        $mod = $cn % 11;
        if ($mod == $value[$ci]) {
            return true;
        } else if (10 == $mod) {
            $cn = $value[0] * 3
                + $value[1] * 4
                + $value[2] * 5
                + $value[3] * 6
                + $value[4] * 7
                + $value[5] * 8
                + $value[6] * 9;

            if (9 == $ci) {
                $cn += $value[7] * 10
                     + $value[8] * 1;
            }

            $mod = $cn % 11;
            if ($mod == $value[$ci]) {
                return true;
            } else if (10 == $mod && 0 === $value[$ci]) {
                return true;
            }
        }

        return false;
    }

}
