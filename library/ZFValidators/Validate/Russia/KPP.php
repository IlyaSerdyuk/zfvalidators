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
 * Валидатор КПП (код причины постановки на учёт),
 * применяемого в налоговой системе Российской Федерации
 * 
 * @see https://ru.wikipedia.org/wiki/Код_причины_постановки_на_учёт 
 */
class ZFValidators_Validate_Russia_KPP extends Zend_Validate_Abstract
{
    
    const INVALID = 'ruKppInvalid';
    
    protected $_messageTemplates = array(
        self::INVALID => "'%value%' is not a KPP"
    );
    
    /**
     * Это корректный КПП?
     * 
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        
        
        $value = strtoupper(strval($value));
        
        if (1 != preg_match('/^[0-9]{4}[0-9A-Z]{2}[0-9]{3}$/', $value)) {
            return false;
        }
        
        return true;
    }
    
}
