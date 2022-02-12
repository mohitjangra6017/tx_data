<?php

class __Mustache_b88b141b8d7a46f5117eb83eb1a62464 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';

        $buffer .= $indent . '<';
        $value = $this->resolveValue($context->find('tag'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= ' id="';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" class="';
        $value = $this->resolveValue($context->find('class'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" data-droptarget="1" data-blockregion="';
        $value = $this->resolveValue($context->find('displayregion'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $buffer .= $indent . '    ';
        $value = $this->resolveValue($context->find('content'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '</';
        $value = $this->resolveValue($context->find('tag'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '>';

        return $buffer;
    }
}
