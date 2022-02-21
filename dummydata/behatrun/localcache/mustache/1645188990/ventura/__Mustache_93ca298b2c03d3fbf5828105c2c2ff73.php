<?php

class __Mustache_93ca298b2c03d3fbf5828105c2c2ff73 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="block_admin_related_pages__itemlist">
';
        $buffer .= $indent . '    <ul class="unlist">
';
        // 'items' section
        $value = $context->find('items');
        $buffer .= $this->sectionC3e3f72c8f2b8255d6d33397c86a3247($context, $indent, $value);
        $buffer .= $indent . '    </ul>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionC3e3f72c8f2b8255d6d33397c86a3247(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <li><a class="block_admin_related_pages__item" href="{{{url}}}">{{label}}</a></li>
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
                
                $buffer .= $indent . '        <li><a class="block_admin_related_pages__item" href="';
                $value = $this->resolveValue($context->find('url'), $context);
                $buffer .= $value;
                $buffer .= '">';
                $value = $this->resolveValue($context->find('label'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a></li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
