<?php

class __Mustache_8b8466f817f3411c8190d740a614a99b extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'pbar' section
        $value = $context->find('pbar');
        $buffer .= $this->sectionCa84e1ccf61a32f62c0cb92371d77828($context, $indent, $value);
        // 'pbar' inverted section
        $value = $context->find('pbar');
        if (empty($value)) {
            
            $buffer .= $indent . '    <span class="label label-default">';
            $value = $this->resolveValue($context->find('statustext'), $context);
            $buffer .= call_user_func($this->mustache->getEscape(), $value);
            $buffer .= '</span>
';
        }
        $buffer .= $indent . '
';

        return $buffer;
    }

    private function sectionCa84e1ccf61a32f62c0cb92371d77828(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    {{> core/progress_bar }}
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
                
                // Totara hack: if the partial starts with "." then try
                // to resolve it from the current context.
                $partialstr = 'core/progress_bar';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context, $indent . '    ');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
