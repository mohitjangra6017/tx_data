<?php

class __Mustache_74464d8f5076365dea556f0c8421717d extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'errors_has_items' section
        $value = $context->find('errors_has_items');
        $buffer .= $this->section96a0a3a72cae8bfd1b0c7dfb9ee53ac8($context, $indent, $value);

        return $buffer;
    }

    private function section1611ee3c8654520a3089f33811249515(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<span id="id_error_{{elementname}}" class="validation-error">{{{message}}}</span><br />';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= '<span id="id_error_';
                $value = $this->resolveValue($context->find('elementname'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="validation-error">';
                $value = $this->resolveValue($context->find('message'), $context);
                $buffer .= $value;
                $buffer .= '</span><br />';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section96a0a3a72cae8bfd1b0c7dfb9ee53ac8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="totara_form-error-container">
        {{#errors}}<span id="id_error_{{elementname}}" class="validation-error">{{{message}}}</span><br />{{/errors}}
    </div>
';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '    <div class="totara_form-error-container">
';
                $buffer .= $indent . '        ';
                // 'errors' section
                $value = $context->find('errors');
                $buffer .= $this->section1611ee3c8654520a3089f33811249515($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
