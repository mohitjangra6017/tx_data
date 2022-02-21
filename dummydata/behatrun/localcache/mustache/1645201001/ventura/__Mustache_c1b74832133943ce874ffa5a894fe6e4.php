<?php

class __Mustache_c1b74832133943ce874ffa5a894fe6e4 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<span class="loading-icon">';
        // 'pix' section
        $value = $context->find('pix');
        $buffer .= $this->section8e79537e56933ae8061da08460ea6ff9($context, $indent, $value);
        $buffer .= '</span>
';

        return $buffer;
    }

    private function section8e79537e56933ae8061da08460ea6ff9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' y/loading, core, loading, core ';
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
                
                $buffer .= ' y/loading, core, loading, core ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
