<?php

class __Mustache_180105be8ecb8ad4729906db38a8f93e extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="tw-toggleFilterPanel"
';
        $buffer .= $indent . '    data-tw-toggleFilterPanel=""
';
        $buffer .= $indent . '    data-core-autoinitialise="true"
';
        $buffer .= $indent . '    data-core-autoinitialise-amd="totara_core/toggle_filter_panel">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <div class="tw-toggleFilterPanel__trigger" data-tw-toggleFilterPanel-trigger="">
';
        $buffer .= $indent . '        <a href="#" class="tw-toggleFilterPanel__trigger_show">
';
        $buffer .= $indent . '            ';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section3a215932c5293a5d2ac836fe8243be9e($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '            <span class="tw-toggleFilterPanel__count" data-tw-toggleFilterPanel-extraContent=""></span>
';
        $buffer .= $indent . '        </a>
';
        $buffer .= $indent . '        <a href="#" class="tw-toggleFilterPanel__trigger_hide">
';
        $buffer .= $indent . '            ';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section91f3ede584423694eaee8bedfda2d93f($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '        </a>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function section3a215932c5293a5d2ac836fe8243be9e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'filtersshow, totara_core';
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
                
                $buffer .= 'filtersshow, totara_core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section91f3ede584423694eaee8bedfda2d93f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'filtershide, totara_core';
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
                
                $buffer .= 'filtershide, totara_core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
