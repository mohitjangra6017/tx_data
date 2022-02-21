<?php

class __Mustache_615467e34d17db5edd3b33d652accc8e extends Mustache_Template
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
