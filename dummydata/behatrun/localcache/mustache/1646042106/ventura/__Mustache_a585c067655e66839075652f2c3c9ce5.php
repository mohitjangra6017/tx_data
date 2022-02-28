<?php

class __Mustache_a585c067655e66839075652f2c3c9ce5 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';

        $buffer .= $indent . '
';
        $value = $this->resolveValue($context->last(), $context);
        $buffer .= $indent . $value;

        return $buffer;
    }
}
