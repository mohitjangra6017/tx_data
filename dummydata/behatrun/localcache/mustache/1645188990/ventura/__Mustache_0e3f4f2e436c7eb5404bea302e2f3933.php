<?php

class __Mustache_0e3f4f2e436c7eb5404bea302e2f3933 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="rb-chartjs"
';
        $buffer .= $indent . '    style="max-width: 100%; max-height: 100%; ';
        // 'height' section
        $value = $context->find('height');
        $buffer .= $this->section9c08e206499ef8406876708d979747d6($context, $indent, $value);
        // 'width' section
        $value = $context->find('width');
        $buffer .= $this->sectionBd6065ee05b8893394ef68791a8bff7f($context, $indent, $value);
        $buffer .= '"
';
        $buffer .= $indent . '    data-core-autoinitialise="true"
';
        $buffer .= $indent . '    data-core-autoinitialise-amd="totara_reportbuilder/resizer"
';
        $buffer .= $indent . '    data-items-per-row="6"
';
        $buffer .= $indent . '>
';
        // 'chart' section
        $value = $context->find('chart');
        $buffer .= $this->sectionE0d04d470fd1c402dcbb59276649e7fd($context, $indent, $value);
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function section9c08e206499ef8406876708d979747d6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'min-height:{{height}}px;';
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
                
                $buffer .= 'min-height:';
                $value = $this->resolveValue($context->find('height'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= 'px;';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBd6065ee05b8893394ef68791a8bff7f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'min-width:{{width}}px;';
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
                
                $buffer .= 'min-width:';
                $value = $this->resolveValue($context->find('width'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= 'px;';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE0d04d470fd1c402dcbb59276649e7fd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="rb-chartjs__chart rb-chartjs__chart--{{type}}">
            <canvas class="rb-chartjs__chart__canvas"
                    role="img"
                    data-core-autoinitialise="true"
                    data-core-autoinitialise-amd="totara_reportbuilder/chartjs"
                    data-report-options="{{settings}}">
            </canvas>
        </div>
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
                
                $buffer .= $indent . '        <div class="rb-chartjs__chart rb-chartjs__chart--';
                $value = $this->resolveValue($context->find('type'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $buffer .= $indent . '            <canvas class="rb-chartjs__chart__canvas"
';
                $buffer .= $indent . '                    role="img"
';
                $buffer .= $indent . '                    data-core-autoinitialise="true"
';
                $buffer .= $indent . '                    data-core-autoinitialise-amd="totara_reportbuilder/chartjs"
';
                $buffer .= $indent . '                    data-report-options="';
                $value = $this->resolveValue($context->find('settings'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $buffer .= $indent . '            </canvas>
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
