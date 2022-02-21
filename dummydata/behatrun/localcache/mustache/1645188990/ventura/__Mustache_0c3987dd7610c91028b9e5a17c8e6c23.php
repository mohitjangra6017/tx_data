<?php

class __Mustache_0c3987dd7610c91028b9e5a17c8e6c23 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div data-core-autoinitialise="true"
';
        $buffer .= $indent . '     data-core-autoinitialise-amd="totara_reportbuilder/report"
';
        $buffer .= $indent . '     data-report-id="';
        $value = $this->resolveValue($context->find('reportid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        // 'can_edit' section
        $value = $context->find('can_edit');
        $buffer .= $this->section0a556716b7fedcadeccf015db22ee2ef($context, $indent, $value);
        // 'can_edit' inverted section
        $value = $context->find('can_edit');
        if (empty($value)) {
            
            $buffer .= $indent . '    <h2>';
            $value = $this->resolveValue($context->find('heading'), $context);
            $buffer .= call_user_func($this->mustache->getEscape(), $value);
            $buffer .= '</h2>
';
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '        ';
        $value = $this->resolveValue($context->find('resultcount'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '</div>';

        return $buffer;
    }

    private function section86933e5bae3ec7be8fc9f744d37e8732(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'edit,editreportttitle,totara_reportbuilder';
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
                
                $buffer .= 'edit,editreportttitle,totara_reportbuilder';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0a556716b7fedcadeccf015db22ee2ef(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <h2 data-core-autoinitialise="true"
        data-core-autoinitialise-amd="totara_core/inline-edit"
        data-inline-edit-allow-empty="false">
        <span data-inline-edit>
            {{heading}}
        </span>
        <input style="display: none" data-inline-edit-input type="text" value="{{fullname}}">
        <a href="#" data-inline-edit-control>
            {{#flex_icon}}edit,editreportttitle,totara_reportbuilder{{/flex_icon}}
        </a>
    </h2>
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
                
                $buffer .= $indent . '    <h2 data-core-autoinitialise="true"
';
                $buffer .= $indent . '        data-core-autoinitialise-amd="totara_core/inline-edit"
';
                $buffer .= $indent . '        data-inline-edit-allow-empty="false">
';
                $buffer .= $indent . '        <span data-inline-edit>
';
                $buffer .= $indent . '            ';
                $value = $this->resolveValue($context->find('heading'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '
';
                $buffer .= $indent . '        </span>
';
                $buffer .= $indent . '        <input style="display: none" data-inline-edit-input type="text" value="';
                $value = $this->resolveValue($context->find('fullname'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $buffer .= $indent . '        <a href="#" data-inline-edit-control>
';
                $buffer .= $indent . '            ';
                // 'flex_icon' section
                $value = $context->find('flex_icon');
                $buffer .= $this->section86933e5bae3ec7be8fc9f744d37e8732($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '        </a>
';
                $buffer .= $indent . '    </h2>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
