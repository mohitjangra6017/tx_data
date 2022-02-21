<?php

class __Mustache_f5fe94131279bdcec6d34d165e1da8b3 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'block_current_learning/main_content';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context);
        }
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'block_current_learning/paging';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context);
        }
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->sectionA4d9ad74cdc7e4c0549306c34b3b9654($context, $indent, $value);
        $buffer .= $indent . '
';

        return $buffer;
    }

    private function sectionA4d9ad74cdc7e4c0549306c34b3b9654(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'block_current_learning/current_learning\', \'theme_legacy/bootstrap\'], function (current_learning) {
    current_learning.init({{{contextdata}}});
});
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
                
                $buffer .= $indent . 'require([\'block_current_learning/current_learning\', \'theme_legacy/bootstrap\'], function (current_learning) {
';
                $buffer .= $indent . '    current_learning.init(';
                $value = $this->resolveValue($context->find('contextdata'), $context);
                $buffer .= $value;
                $buffer .= ');
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
