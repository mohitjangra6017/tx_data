<?php

class __Mustache_bc28bacc24ef3c5b24115d5cf37cbd5d extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<form data-totara-form
';
        $buffer .= $indent . '      data-element-id="';
        $value = $this->resolveValue($context->find('formid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"
';
        $buffer .= $indent . '      id="';
        $value = $this->resolveValue($context->find('formid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"
';
        $buffer .= $indent . '      autocomplete="off" action="';
        $value = $this->resolveValue($context->find('action'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"
';
        $buffer .= $indent . '      method="post"
';
        $buffer .= $indent . '      accept-charset="utf-8"
';
        $buffer .= $indent . '      class="totara_form ';
        $value = $this->resolveValue($context->find('cssclass'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= ' clearfix">
';
        $buffer .= $indent . '    <span>';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->sectionE46b8206699723f79752e303e16a7643($context, $indent, $value);
        $buffer .= '</span>
';
        // 'items' section
        $value = $context->find('items');
        $buffer .= $this->sectionCb18a2f5efb9f9a1913389af71d65fe1($context, $indent, $value);
        $buffer .= $indent . '    <div style="display: none;">
';
        $buffer .= $indent . '        <input type="hidden" name="___tf_formclass" value="';
        $value = $this->resolveValue($context->find('phpclass'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $buffer .= $indent . '        <input type="hidden" name="___tf_idsuffix" value="';
        $value = $this->resolveValue($context->find('idsuffix'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $buffer .= $indent . '        <input type="hidden" name="___tf_reload" value="">
';
        $buffer .= $indent . '        <input type="hidden" name="sesskey" value="';
        $value = $this->resolveValue($context->find('sesskey'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</form>
';
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->section80c32aed86ce5e473f2be9b1b7eaae91($context, $indent, $value);

        return $buffer;
    }

    private function sectionE46b8206699723f79752e303e16a7643(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'manuallymarkwhencomplete, core_completion';
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
                
                $buffer .= 'manuallymarkwhencomplete, core_completion';
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

    private function sectionCb18a2f5efb9f9a1913389af71d65fe1(Mustache_Context $context, $indent, $value)
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
                
                $buffer .= $indent . '        <div data-item-classification="';
                $value = $this->resolveValue($context->find('elementclassification'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                data-element-type="';
                $value = $this->resolveValue($context->find('elementtype'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                data-element-id="';
                $value = $this->resolveValue($context->find('elementid'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                data-element-template="';
                $value = $this->resolveValue($context->find('form_item_template'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                data-element-frozen="';
                $value = $this->resolveValue($context->find('frozen'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '            ';
                // 'amdmodule' section
                $value = $context->find('amdmodule');
                $buffer .= $this->section6a0f1cad00cfc1cdca71efe685d64e4d($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                class="nostyles">
';
                // Totara hack: if the partial starts with "." then try
                // to resolve it from the current context.
                $partialstr = '&&form_item_template';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context, $indent . '            ');
                }
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section80c32aed86ce5e473f2be9b1b7eaae91(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'jquery\', \'totara_form/form\'], function ($, Form) {
    Form.init({
        id: \'{{formid}}\',
        actionsConfig: {{{actionsjson}}}
    });
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
                
                $buffer .= $indent . 'require([\'jquery\', \'totara_form/form\'], function ($, Form) {
';
                $buffer .= $indent . '    Form.init({
';
                $buffer .= $indent . '        id: \'';
                $value = $this->resolveValue($context->find('formid'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '\',
';
                $buffer .= $indent . '        actionsConfig: ';
                $value = $this->resolveValue($context->find('actionsjson'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
