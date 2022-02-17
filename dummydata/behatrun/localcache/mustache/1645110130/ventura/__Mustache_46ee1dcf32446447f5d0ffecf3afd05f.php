<?php

class __Mustache_46ee1dcf32446447f5d0ffecf3afd05f extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="tf_group totara_form_group_section clearfix">
';
        $buffer .= $indent . '    <fieldset id="';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" class="';
        // 'collapsible' section
        $value = $context->find('collapsible');
        $buffer .= $this->sectionB0f1e58aeb699165e6e3b284b372cf2e($context, $indent, $value);
        $buffer .= '">
';
        $buffer .= $indent . '        ';
        // 'collapsible' section
        $value = $context->find('collapsible');
        $buffer .= $this->section89ba96cbb47211d23ae07934ea8b8978($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '        <legend class="tf_section_legend">';
        $value = $this->resolveValue($context->find('legend'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '</legend>';
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'totara_form/help_button';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context);
        }
        $buffer .= '
';
        $buffer .= $indent . '        <div class="tf_section_items">
';
        // 'items' section
        $value = $context->find('items');
        $buffer .= $this->sectionB2af06a7a425e7c1e064b113dcb83197($context, $indent, $value);
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </fieldset>
';
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'totara_form/validation_errors';
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

    private function sectionB6260f765d3bb5f7c47e0198c5ceb48c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'collapsible';
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
                
                $buffer .= 'collapsible';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB0f1e58aeb699165e6e3b284b372cf2e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#expanded}}collapsible{{/expanded}}{{^expanded}}collapsed{{/expanded}}';
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
                
                // 'expanded' section
                $value = $context->find('expanded');
                $buffer .= $this->sectionB6260f765d3bb5f7c47e0198c5ceb48c($context, $indent, $value);
                // 'expanded' inverted section
                $value = $context->find('expanded');
                if (empty($value)) {
                    
                    $buffer .= 'collapsed';
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1bd0cc4642e36d67e46c9dd550f1fa06(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '1';
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
                
                $buffer .= '1';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section89ba96cbb47211d23ae07934ea8b8978(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<input type="hidden" class="tf_section_collapsible" name="{{name}}[expanded]" value="{{#expanded}}1{{/expanded}}{{^expanded}}0{{/expanded}}" />';
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
                
                $buffer .= '<input type="hidden" class="tf_section_collapsible" name="';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '[expanded]" value="';
                // 'expanded' section
                $value = $context->find('expanded');
                $buffer .= $this->section1bd0cc4642e36d67e46c9dd550f1fa06($context, $indent, $value);
                // 'expanded' inverted section
                $value = $context->find('expanded');
                if (empty($value)) {
                    
                    $buffer .= '0';
                }
                $buffer .= '" />';
                $context->pop();
            }
        }
    
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

    private function sectionB2af06a7a425e7c1e064b113dcb83197(Mustache_Context $context, $indent, $value)
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
                {{> &&form_item_template }}
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
                
                $buffer .= $indent . '            <div data-item-classification="';
                $value = $this->resolveValue($context->find('elementclassification'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                 data-element-type="';
                $value = $this->resolveValue($context->find('elementtype'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                 data-element-id="';
                $value = $this->resolveValue($context->find('elementid'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                 data-element-template="';
                $value = $this->resolveValue($context->find('form_item_template'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                 data-element-frozen="';
                $value = $this->resolveValue($context->find('frozen'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                ';
                // 'amdmodule' section
                $value = $context->find('amdmodule');
                $buffer .= $this->section6a0f1cad00cfc1cdca71efe685d64e4d($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                 class="nostyles">
';
                // Totara hack: if the partial starts with "." then try
                // to resolve it from the current context.
                $partialstr = '&&form_item_template';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context, $indent . '                ');
                }
                $buffer .= $indent . '            </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
