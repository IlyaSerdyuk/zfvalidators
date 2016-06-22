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
 * Валидатор СНИЛС (страховой номер индивидуального лицевого счёта),
 * применяемого в пенсионной системе Российской Федерации
 * 
 * @see https://ru.wikipedia.org/wiki/Страховой_номер_индивидуального_лицевого_счёта
 */
class ZFValidators_Validate_Russia_SNILS extends Zend_Validate_Abstract
{
    
    const INVALID = 'ruSnilsInvalid';
    
    protected $_messageTemplates = array(
        self::INVALID => "'%value%' is not a SNILS"
    );
    
    /**
     * Это корректный СНИЛС?
     * 
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        
        
        $value = (string) $value;
        
        if ('001-001' == substr($value, 0, 7) && '999' != substr($value, 8, 3)) {
            return true;
        }
        
        if (1 != preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{3} [0-9]{2}$/', $value)) {
            return false;
        }
        
        
        $cc = $value[0] * 9
            + $value[1] * 8
            + $value[2] * 7
            + $value[3] * 6
            + $value[4] * 5
            + $value[5] * 4
            + $value[6] * 3
            + $value[7] * 2
            + $value[8] * 1;
        $ct = substr($value, 12);
        
        if ($cc < 100 && $cc == $ct) {
            return true;
        } else if (($cc == '100' || $cc == '101') && $ct == '00') {
            return true;
        } else if ($cc % 101 == $ct) {
            return true;
        } else {
            return false;
        }
    }
    
}
