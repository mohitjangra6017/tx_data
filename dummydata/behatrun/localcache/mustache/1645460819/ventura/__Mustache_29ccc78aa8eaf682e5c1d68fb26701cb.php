<?php

class __Mustache_29ccc78aa8eaf682e5c1d68fb26701cb extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="singleselect">
';
        // 'hasblocks' section
        $value = $context->find('hasblocks');
        $buffer .= $this->section0cadf73059fb2605622863a78e1bd4c3($context, $indent, $value);
        // 'hasblocks' inverted section
        $value = $context->find('hasblocks');
        if (empty($value)) {
            
            $buffer .= $indent . '    ';
            // 'str' section
            $value = $context->find('str');
            $buffer .= $this->section1d8b7b3179861a926c963f76b049682e($context, $indent, $value);
            $buffer .= '
';
        }
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionFaa400e102d72a0892f33e50a3c91042(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <input type="hidden" name="{{name}}" value="{{value}}" />
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
                
                $buffer .= $indent . '            <input type="hidden" name="';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" value="';
                $value = $this->resolveValue($context->find('value'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" />
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section36d0242880fd41b624218ba4c8470eb3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> &&template }}';
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
                $partialstr = '&&template';
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

    private function section52344b9f0921479058f628497bd49678(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#context}}{{> &&template }}{{/context}}';
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
                
                // 'context' section
                $value = $context->find('context');
                $buffer .= $this->section36d0242880fd41b624218ba4c8470eb3($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section857632106cfa040c9739ea73713f3a5f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'noscript, core';
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
                
                $buffer .= 'noscript, core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0cadf73059fb2605622863a78e1bd4c3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '

    <div class="addBlock">
        <form method="get" action="{{actionurl}}">
            {{#hidden}}
            <input type="hidden" name="{{name}}" value="{{value}}" />
            {{/hidden}}
            <input type="hidden" name="bui_addblock" value="">
        </form>
        <button class="addBlock--trigger" data-addblock="{{addblockregion}}">
            {{#plusicon}}{{#context}}{{> &&template }}{{/context}}{{/plusicon}}
        </button>

        <noscript>{{#str}}noscript, core{{/str}}</noscript>
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
                
                $buffer .= $indent . '
';
                $buffer .= $indent . '    <div class="addBlock">
';
                $buffer .= $indent . '        <form method="get" action="';
                $value = $this->resolveValue($context->find('actionurl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                // 'hidden' section
                $value = $context->find('hidden');
                $buffer .= $this->sectionFaa400e102d72a0892f33e50a3c91042($context, $indent, $value);
                $buffer .= $indent . '            <input type="hidden" name="bui_addblock" value="">
';
                $buffer .= $indent . '        </form>
';
                $buffer .= $indent . '        <button class="addBlock--trigger" data-addblock="';
                $value = $this->resolveValue($context->find('addblockregion'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $buffer .= $indent . '            ';
                // 'plusicon' section
                $value = $context->find('plusicon');
                $buffer .= $this->section52344b9f0921479058f628497bd49678($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '        </button>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        <noscript>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section857632106cfa040c9739ea73713f3a5f($context, $indent, $value);
                $buffer .= '</noscript>
';
                $buffer .= $indent . '    </div>
';
                $buffer .= $indent . '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1d8b7b3179861a926c963f76b049682e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'noblockstoaddhere, core';
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
                
                $buffer .= 'noblockstoaddhere, core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
