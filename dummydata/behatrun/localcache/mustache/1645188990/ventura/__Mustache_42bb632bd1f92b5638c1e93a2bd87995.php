<?php

class __Mustache_42bb632bd1f92b5638c1e93a2bd87995 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="tw-selectSearchText"
';
        $buffer .= $indent . '    data-tw-selectorGroup=""
';
        $buffer .= $indent . '    data-core-autoinitialise="true"
';
        $buffer .= $indent . '    data-core-autoinitialise-amd="totara_core/select_search_text">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <label for="';
        $value = $this->resolveValue($context->find('key'), $context);
        $buffer .= $value;
        $buffer .= '_input" class="tw-selectSearchText__header';
        // 'title_hidden' section
        $value = $context->find('title_hidden');
        $buffer .= $this->section31d5f7b95d838b496f099af1ee84afb5($context, $indent, $value);
        $buffer .= '">
';
        $buffer .= $indent . '        ';
        $value = $this->resolveValue($context->find('title'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '    </label>
';
        $buffer .= $indent . '
';
        // 'has_hint_icon' section
        $value = $context->find('has_hint_icon');
        $buffer .= $this->sectionA4bb4685ea69958f83c61a00009cdb2a($context, $indent, $value);
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <div class="tw-selectSearchText__field">
';
        $buffer .= $indent . '        <input type="text" value="';
        $value = $this->resolveValue($context->find('current_val'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"
';
        $buffer .= $indent . '        ';
        // 'placeholder_show' section
        $value = $context->find('placeholder_show');
        $buffer .= $this->sectionFfc0246697e9109acaa3b0c5ad36a7a7($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '        class="tw-selectSearchText__field_input" id="';
        $value = $this->resolveValue($context->find('key'), $context);
        $buffer .= $value;
        $buffer .= '_input"
';
        $buffer .= $indent . '        data-tw-selectSearchText-urlkey="';
        $value = $this->resolveValue($context->find('key'), $context);
        $buffer .= $value;
        $buffer .= '"';
        // 'current_val' section
        $value = $context->find('current_val');
        $buffer .= $this->section5f906d3d2c8021b2a6a835c6caf56c6b($context, $indent, $value);
        $buffer .= '>
';
        $buffer .= $indent . '        <a href="#" class="tw-selectSearchText__field_clear tw-selectSearchText__hidden"
';
        $buffer .= $indent . '        data-tw-selectSearchText-clear="">
';
        $buffer .= $indent . '            ';
        // 'flex_icon' section
        $value = $context->find('flex_icon');
        $buffer .= $this->section07e0897ee83e0420df5e7e56ebcce1ec($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '        </a>
';
        $buffer .= $indent . '    </div>';
        $buffer .= '<button class="tw-selectSearchText__btn" data-tw-selectSearchText-trigger="">
';
        $buffer .= $indent . '        ';
        // 'flex_icon' section
        $value = $context->find('flex_icon');
        $buffer .= $this->sectionC0945e544427e3b01fb2df189bc2c414($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '    </button>
';
        $buffer .= $indent . '</div>';

        return $buffer;
    }

    private function section31d5f7b95d838b496f099af1ee84afb5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' sr-only';
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
                
                $buffer .= ' sr-only';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9f5a707903137d5662ec84184a401e7e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> core/help_icon}}';
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
                $partialstr = 'core/help_icon';
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

    private function sectionA4bb4685ea69958f83c61a00009cdb2a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
     <div class="tw-selectSearchText__hint">
         {{#hint_icon}}{{> core/help_icon}}{{/hint_icon}}
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
                
                $buffer .= $indent . '     <div class="tw-selectSearchText__hint">
';
                $buffer .= $indent . '         ';
                // 'hint_icon' section
                $value = $context->find('hint_icon');
                $buffer .= $this->section9f5a707903137d5662ec84184a401e7e($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '     </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionCcacbc7456ee2adffcc3706bf29f8f99(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'placeholder="{{title}}"';
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
                
                $buffer .= 'placeholder="';
                $value = $this->resolveValue($context->find('title'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFfc0246697e9109acaa3b0c5ad36a7a7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#title_hidden}}placeholder="{{title}}"{{/title_hidden}}';
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
                
                // 'title_hidden' section
                $value = $context->find('title_hidden');
                $buffer .= $this->sectionCcacbc7456ee2adffcc3706bf29f8f99($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5f906d3d2c8021b2a6a835c6caf56c6b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' data-tw-selector-active="true"';
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
                
                $buffer .= ' data-tw-selector-active="true"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section07e0897ee83e0420df5e7e56ebcce1ec(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'close, removesearchtext, totara_core,';
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
                
                $buffer .= 'close, removesearchtext, totara_core,';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC0945e544427e3b01fb2df189bc2c414(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'search, search, core,';
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
                
                $buffer .= 'search, search, core,';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
