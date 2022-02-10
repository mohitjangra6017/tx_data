<?php

class __Mustache_6f47c3634b60d9366813f26d3f72e4b9 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';

        $buffer .= $indent . '<div class="totaraNav_prim--toggleNav">
';
        $buffer .= $indent . '    <a href="#" class="totaraNav_prim--toggleNav_target" data-tw-totaraNav-toggle="" role="button" aria-expanded="false">
';
        $buffer .= $indent . '        ';
        $value = $this->resolveValue($context->find('burger_icon'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '    </a>
';
        $buffer .= $indent . '</div>';

        return $buffer;
    }
}
