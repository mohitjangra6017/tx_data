<?php

class __Mustache_8974ed447484d882bf6873a12045e670 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="block_totara_community">
';
        $buffer .= $indent . '    <a href="https://totara.community">';
        // 'pix' section
        $value = $context->find('pix');
        $buffer .= $this->sectionF0d6bceb422ef417ee04485be259c4a5($context, $indent, $value);
        $buffer .= '</a>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <p>
';
        $buffer .= $indent . '        ';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section5fb8285f950f5a1801d7a330d0d6f619($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '    </p>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionF0d6bceb422ef417ee04485be259c4a5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'totara-community, block_totara_community';
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
                
                $buffer .= 'totara-community, block_totara_community';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5fb8285f950f5a1801d7a330d0d6f619(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'totara_community:cta, block_totara_community';
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
                
                $buffer .= 'totara_community:cta, block_totara_community';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
