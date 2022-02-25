<?php

class __Mustache_3e480b4b15584c8f4f75592ef5ff4823 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<ul';
        // 'ulattrs' section
        $value = $context->find('ulattrs');
        $buffer .= $this->section2b9b5aad2fccc17c960c5815f82f8ff6($context, $indent, $value);
        $buffer .= '>
';
        // 'items' section
        $value = $context->find('items');
        $buffer .= $this->sectionBb0feed522893602e194fa78f9f75f5d($context, $indent, $value);
        $buffer .= $indent . '</ul>
';

        return $buffer;
    }

    private function section2b9b5aad2fccc17c960c5815f82f8ff6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' {{key}}="{{value}}"';
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
                $value = $this->resolveValue($context->find('key'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '="';
                $value = $this->resolveValue($context->find('value'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBb0feed522893602e194fa78f9f75f5d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <li {{#liattrs}} {{key}}="{{value}}"{{/liattrs}}>
            <p{{#pattrs}} {{key}}="{{value}}"{{/pattrs}}>{{{pcontent}}}</p>
            {{{licontent}}}
        </li>
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
                
                $buffer .= $indent . '        <li ';
                // 'liattrs' section
                $value = $context->find('liattrs');
                $buffer .= $this->section2b9b5aad2fccc17c960c5815f82f8ff6($context, $indent, $value);
                $buffer .= '>
';
                $buffer .= $indent . '            <p';
                // 'pattrs' section
                $value = $context->find('pattrs');
                $buffer .= $this->section2b9b5aad2fccc17c960c5815f82f8ff6($context, $indent, $value);
                $buffer .= '>';
                $value = $this->resolveValue($context->find('pcontent'), $context);
                $buffer .= $value;
                $buffer .= '</p>
';
                $buffer .= $indent . '            ';
                $value = $this->resolveValue($context->find('licontent'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '        </li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
