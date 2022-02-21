<?php

class __Mustache_3b11081ff4f8171995680c184c87b13f extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="skiplinks">
';
        // 'links' section
        $value = $context->find('links');
        $buffer .= $this->section96ef96637f36d45706a9d82f290bb9d6($context, $indent, $value);
        $buffer .= $indent . '</div>
';
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->sectionBa3dd610f3a5b54b81a415fa8345a695($context, $indent, $value);

        return $buffer;
    }

    private function section96ef96637f36d45706a9d82f290bb9d6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <a href="#{{{url}}}" class="skip">{{{text}}}</a>
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
                
                $buffer .= $indent . '    <a href="#';
                $value = $this->resolveValue($context->find('url'), $context);
                $buffer .= $value;
                $buffer .= '" class="skip">';
                $value = $this->resolveValue($context->find('text'), $context);
                $buffer .= $value;
                $buffer .= '</a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBa3dd610f3a5b54b81a415fa8345a695(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'core/yui\'], function(Y) {
    M.util.init_skiplink(Y);
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
                
                $buffer .= $indent . 'require([\'core/yui\'], function(Y) {
';
                $buffer .= $indent . '    M.util.init_skiplink(Y);
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
