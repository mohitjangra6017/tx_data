<?php

class __Mustache_89b7f2b79d4d310bec21b8d1cdefb588 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="tw-selectMulti"
';
        $buffer .= $indent . '    data-tw-selectorGroup=""
';
        $buffer .= $indent . '    data-core-autoinitialise="true"
';
        $buffer .= $indent . '    data-core-autoinitialise-amd="totara_core/select_multi"
';
        $buffer .= $indent . '    data-tw-selectMulti-key="';
        $value = $this->resolveValue($context->find('key'), $context);
        $buffer .= $value;
        $buffer .= '">
';
        $buffer .= $indent . '
';
        // 'title_hidden' inverted section
        $value = $context->find('title_hidden');
        if (empty($value)) {
            
            $buffer .= $indent . '    <h3 id="';
            $value = $this->resolveValue($context->find('key'), $context);
            $buffer .= $value;
            $buffer .= '" class="tw-selectMulti__header">
';
            $buffer .= $indent . '        ';
            $value = $this->resolveValue($context->find('title'), $context);
            $buffer .= $value;
            $buffer .= '
';
            $buffer .= $indent . '    </h3>
';
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <ul class="tw-selectMulti__list"
';
        $buffer .= $indent . '        role="listbox"
';
        $buffer .= $indent . '        aria-multiselectable="true"
';
        $buffer .= $indent . '        ';
        // 'title' section
        $value = $context->find('title');
        $buffer .= $this->section744c3b5a92edb97c99a6d3e149c13731($context, $indent, $value);
        $buffer .= '>
';
        $buffer .= $indent . '
';
        // 'options' section
        $value = $context->find('options');
        $buffer .= $this->section68285fe900db78394e8c6fe50cb4ec97($context, $indent, $value);
        $buffer .= $indent . '    </ul>
';
        $buffer .= $indent . '</div>';

        return $buffer;
    }

    private function section744c3b5a92edb97c99a6d3e149c13731(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'aria-labelledby="{{{key}}}"';
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
                
                $buffer .= 'aria-labelledby="';
                $value = $this->resolveValue($context->find('key'), $context);
                $buffer .= $value;
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9f4f9ffdd8b5793fdbeb983fff1d4757(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' tw-selectMulti__link_active';
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
                
                $buffer .= ' tw-selectMulti__link_active';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section77fe6ccf06f2792735e9c29e81406a46(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' data-tw-selector-active="{{active}}"';
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
                
                $buffer .= ' data-tw-selector-active="';
                $value = $this->resolveValue($context->find('active'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section03a2cb78adf693fb240638cbbc7ea15e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'true';
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
                
                $buffer .= 'true';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section205e4dfbb461850e398a3d869f37dde4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'close, remove, core,';
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
                
                $buffer .= 'close, remove, core,';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section68285fe900db78394e8c6fe50cb4ec97(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <li class="tw-selectMulti__list_item">
            <a href="#" class="tw-selectMulti__link{{#active}} tw-selectMulti__link_active{{/active}}"
                data-tw-selectMulti-optionKey="{{{key}}}"{{#active}} data-tw-selector-active="{{active}}"{{/active}}
                aria-selected="{{#active}}true{{/active}}{{^active}}false{{/active}}" role="option">
                <span class="tw-selectMulti__link_text">
                    {{{name}}}
                </span>
                <span class="tw-selectMulti__link_close{{^active}} tw-selectMulti__hidden{{/active}}"
                data-tw-selectMulti-close="">
                    {{#flex_icon}}close, remove, core,{{/flex_icon}}
                </span>
            </a>
        </li>
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
                
                $buffer .= $indent . '        <li class="tw-selectMulti__list_item">
';
                $buffer .= $indent . '            <a href="#" class="tw-selectMulti__link';
                // 'active' section
                $value = $context->find('active');
                $buffer .= $this->section9f4f9ffdd8b5793fdbeb983fff1d4757($context, $indent, $value);
                $buffer .= '"
';
                $buffer .= $indent . '                data-tw-selectMulti-optionKey="';
                $value = $this->resolveValue($context->find('key'), $context);
                $buffer .= $value;
                $buffer .= '"';
                // 'active' section
                $value = $context->find('active');
                $buffer .= $this->section77fe6ccf06f2792735e9c29e81406a46($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                aria-selected="';
                // 'active' section
                $value = $context->find('active');
                $buffer .= $this->section03a2cb78adf693fb240638cbbc7ea15e($context, $indent, $value);
                // 'active' inverted section
                $value = $context->find('active');
                if (empty($value)) {
                    
                    $buffer .= 'false';
                }
                $buffer .= '" role="option">
';
                $buffer .= $indent . '                <span class="tw-selectMulti__link_text">
';
                $buffer .= $indent . '                    ';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '                </span>
';
                $buffer .= $indent . '                <span class="tw-selectMulti__link_close';
                // 'active' inverted section
                $value = $context->find('active');
                if (empty($value)) {
                    
                    $buffer .= ' tw-selectMulti__hidden';
                }
                $buffer .= '"
';
                $buffer .= $indent . '                data-tw-selectMulti-close="">
';
                $buffer .= $indent . '                    ';
                // 'flex_icon' section
                $value = $context->find('flex_icon');
                $buffer .= $this->section205e4dfbb461850e398a3d869f37dde4($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                </span>
';
                $buffer .= $indent . '            </a>
';
                $buffer .= $indent . '        </li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
