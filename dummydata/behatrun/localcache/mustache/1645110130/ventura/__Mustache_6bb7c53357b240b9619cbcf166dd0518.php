<?php

class __Mustache_6bb7c53357b240b9619cbcf166dd0518 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="tf_group totara_form_group_buttons clearfix">
';
        // 'items' section
        $value = $context->find('items');
        $buffer .= $this->sectionCf7d1531a1eeadd889c51806ac4839e9($context, $indent, $value);
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'totara_form/help_button';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context, $indent . '    ');
        }
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function section6a0f1cad00cfc1cdca71efe685d64e4d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' data-element-amd-module="{{.}}"';
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
                
                $buffer .= ' data-element-amd-module="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionCf7d1531a1eeadd889c51806ac4839e9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div data-item-classification="{{elementclassification}}"
             data-element-type="{{elementtype}}"
             data-element-id="{{elementid}}"
             data-element-template="{{form_item_template}}"
             data-element-frozen="{{frozen}}"
             {{#amdmodule}} data-element-amd-module="{{.}}"{{/amdmodule}}
             class="nostyles">
        {{> &&form_item_template}}
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
                
                $buffer .= $indent . '        <div data-item-classification="';
                $value = $this->resolveValue($context->find('elementclassification'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '             data-element-type="';
                $value = $this->resolveValue($context->find('elementtype'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '             data-element-id="';
                $value = $this->resolveValue($context->find('elementid'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '             data-element-template="';
                $value = $this->resolveValue($context->find('form_item_template'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '             data-element-frozen="';
                $value = $this->resolveValue($context->find('frozen'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '             ';
                // 'amdmodule' section
                $value = $context->find('amdmodule');
                $buffer .= $this->section6a0f1cad00cfc1cdca71efe685d64e4d($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '             class="nostyles">
';
                // Totara hack: if the partial starts with "." then try
                // to resolve it from the current context.
                $partialstr = '&&form_item_template';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context, $indent . '        ');
                }
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
