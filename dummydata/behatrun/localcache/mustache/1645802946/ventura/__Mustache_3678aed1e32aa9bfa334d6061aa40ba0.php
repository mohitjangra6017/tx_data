<?php

class __Mustache_3678aed1e32aa9bfa334d6061aa40ba0 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<span aria-hidden="true" data-flex-icon="spacer" class="flex-icon ft-fw ft ft-spacer';
        // 'customdata.classes' section
        $value = $context->findDot('customdata.classes');
        $buffer .= $this->section18a9036e3be3edaf79f9c57e4ff01119($context, $indent, $value);
        $buffer .= '"></span>
';

        return $buffer;
    }

    private function section18a9036e3be3edaf79f9c57e4ff01119(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' {{{.}}}';
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
                
                $buffer .= ' ';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= $value;
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
