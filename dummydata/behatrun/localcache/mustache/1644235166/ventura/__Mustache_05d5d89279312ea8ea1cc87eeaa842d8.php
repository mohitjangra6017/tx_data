<?php

class __Mustache_05d5d89279312ea8ea1cc87eeaa842d8 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="tf_element totara_form_element_checkbox">
';
        $buffer .= $indent . '    ';
        // 'frozen' inverted section
        $value = $context->find('frozen');
        if (empty($value)) {
            
            $buffer .= '<input type="hidden" name="';
            $value = $this->resolveValue($context->find('name'), $context);
            $buffer .= call_user_func($this->mustache->getEscape(), $value);
            $buffer .= '" value="';
            $value = $this->resolveValue($context->find('uncheckedvalue'), $context);
            $buffer .= call_user_func($this->mustache->getEscape(), $value);
            $buffer .= '" />';
        }
        $buffer .= '
';
        $buffer .= $indent . '    <div class="tf_element_title"><label class="legend" id="';
        $value = $this->resolveValue($context->find('legendid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" for="';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">';
        $value = $this->resolveValue($context->find('label'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        // 'required' section
        $value = $context->find('required');
        $buffer .= $this->sectionAbcd695a6a7073754755057a962a2a9f($context, $indent, $value);
        $buffer .= '</label>';
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'totara_form/help_button';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context);
        }
        $buffer .= '</div>
';
        $buffer .= $indent . '    <div class="tf_element_input" aria-labelledby="';
        $value = $this->resolveValue($context->find('legendid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" role="group" ';
        // 'errors_has_items' section
        $value = $context->find('errors_has_items');
        $buffer .= $this->section7ef8ec3a0826e301b52f0cd4de51c704($context, $indent, $value);
        $buffer .= '>';
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'totara_form/validation_errors';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context);
        }
        $buffer .= '<input type="checkbox" id="';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" ';
        // 'frozen' section
        $value = $context->find('frozen');
        $buffer .= $this->sectionA8c5327af92c2b0cc77d0753ac6988f9($context, $indent, $value);
        $buffer .= 'name="';
        $value = $this->resolveValue($context->find('name'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" value="';
        $value = $this->resolveValue($context->find('checkedvalue'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"
';
        $buffer .= $indent . '                                                                            data-value-unchecked="';
        $value = $this->resolveValue($context->find('uncheckedvalue'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" ';
        // 'checked' section
        $value = $context->find('checked');
        $buffer .= $this->section51d7c9c652ced0161cb587bacd669266($context, $indent, $value);
        $buffer .= ' ';
        // 'required' section
        $value = $context->find('required');
        $buffer .= $this->section5197bd4c78ccebd47c9f052795fcb4e4($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '    /><span class="tf_loading" style="display: none;">';
        // 'pix' section
        $value = $context->find('pix');
        $buffer .= $this->section25d5f7aeb18117c8aa48b8caac4950c9($context, $indent, $value);
        $buffer .= '</span></div>
';
        $buffer .= $indent . '</div>';

        return $buffer;
    }

    private function sectionAbcd695a6a7073754755057a962a2a9f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> totara_form/required_suffix}}';
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
                $partialstr = 'totara_form/required_suffix';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context);
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB49b14284ca5542198d20d664f92e91c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'id_error_{{.}} ';
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
                
                $buffer .= 'id_error_';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ' ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7ef8ec3a0826e301b52f0cd4de51c704(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'aria-describedby="{{#errorelementnames}}id_error_{{.}} {{/errorelementnames}}"';
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
                
                $buffer .= 'aria-describedby="';
                // 'errorelementnames' section
                $value = $context->find('errorelementnames');
                $buffer .= $this->sectionB49b14284ca5542198d20d664f92e91c($context, $indent, $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA8c5327af92c2b0cc77d0753ac6988f9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'disabled data-';
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
                
                $buffer .= 'disabled data-';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section51d7c9c652ced0161cb587bacd669266(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'checked';
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
                
                $buffer .= 'checked';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5197bd4c78ccebd47c9f052795fcb4e4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'required';
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
                
                $buffer .= 'required';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section25d5f7aeb18117c8aa48b8caac4950c9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' i/loading, core, loading, totara_core';
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
                
                $buffer .= ' i/loading, core, loading, totara_core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
