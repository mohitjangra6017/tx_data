<?php

class __Mustache_4cf04db50f5f00c16497f5ee1981b934 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'pix' section
        $value = $context->find('pix');
        $buffer .= $this->sectionBe33b80f9dc24e2724de08e16c2e64a2($context, $indent, $value);
        $buffer .= '
';

        return $buffer;
    }

    private function sectionBe33b80f9dc24e2724de08e16c2e64a2(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'y/loading, core, loading, mod_assign';
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
                
                $buffer .= $indent . 'y/loading, core, loading, mod_assign';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
