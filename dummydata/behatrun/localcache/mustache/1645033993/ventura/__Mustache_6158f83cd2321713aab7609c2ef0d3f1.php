<?php

class __Mustache_6158f83cd2321713aab7609c2ef0d3f1 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="popover__wrapper" data-enhanced="false" data-title="';
        $value = $this->resolveValue($context->find('title'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" data-component="/core/output/popover"
';
        $buffer .= $indent . '    ';
        // 'arrow_placement' section
        $value = $context->find('arrow_placement');
        $buffer .= $this->section5f5bd68507653b1ada54925587545371($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '    ';
        // 'close_on_focus_out' section
        $value = $context->find('close_on_focus_out');
        $buffer .= $this->section46c3051731e0baa3c88011d7dbd7b367($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '    ';
        // 'placement_max_height' section
        $value = $context->find('placement_max_height');
        $buffer .= $this->sectionD7c05111bf3058d5fdf53afd22deb1f2($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '    ';
        // 'placement_max_width' section
        $value = $context->find('placement_max_width');
        $buffer .= $this->sectionD7bba8d7a1a3f77c38fbf9f75a888b70($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '    ';
        // 'trigger' section
        $value = $context->find('trigger');
        $buffer .= $this->sectionCea835e64dde18ca058151ee0ed011a4($context, $indent, $value);
        $buffer .= '>
';
        $buffer .= $indent . '    <div class="popover__content">
';
        // 'contenttemplatecontext' section
        $value = $context->find('contenttemplatecontext');
        $buffer .= $this->section51978e36aa98be669d3c1c40766f280a($context, $indent, $value);
        // 'contenttemplatecontext' inverted section
        $value = $context->find('contenttemplatecontext');
        if (empty($value)) {
            
            $buffer .= $indent . '            ';
            $value = $this->resolveValue($context->find('contentraw'), $context);
            $buffer .= $value;
            $buffer .= '
';
        }
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    <div class="popover__template">
';
        $buffer .= $indent . '        <div class="popover" role="tooltip">
';
        $buffer .= $indent . '            <div class="arrow"></div>
';
        $buffer .= $indent . '            <h3 class="popover-title"></h3>
';
        $buffer .= $indent . '            <div class="popover-content"></div>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</div>
';
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->section1b67d7093950e0072c8453d02802e447($context, $indent, $value);

        return $buffer;
    }

    private function section5f5bd68507653b1ada54925587545371(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'data-arrow_placement="{{arrow_placement}}"';
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
                
                $buffer .= 'data-arrow_placement="';
                $value = $this->resolveValue($context->find('arrow_placement'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section46c3051731e0baa3c88011d7dbd7b367(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'data-close_on_focus_out=""';
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
                
                $buffer .= 'data-close_on_focus_out=""';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD7c05111bf3058d5fdf53afd22deb1f2(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'data-placement_max_height="{{max_height}}"';
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
                
                $buffer .= 'data-placement_max_height="';
                $value = $this->resolveValue($context->find('max_height'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD7bba8d7a1a3f77c38fbf9f75a888b70(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'data-placement_max_width="{{max_width}}"';
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
                
                $buffer .= 'data-placement_max_width="';
                $value = $this->resolveValue($context->find('max_width'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionCea835e64dde18ca058151ee0ed011a4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'data-trigger="{{trigger}}"';
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
                
                $buffer .= 'data-trigger="';
                $value = $this->resolveValue($context->find('trigger'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section51978e36aa98be669d3c1c40766f280a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{> &&contenttemplate}}
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
                
                // Totara hack: if the partial starts with "." then try
                // to resolve it from the current context.
                $partialstr = '&&contenttemplate';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context, $indent . '            ');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1b67d7093950e0072c8453d02802e447(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'core/popover\'], function (Popover) {
    Popover.scan();
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
                
                $buffer .= $indent . 'require([\'core/popover\'], function (Popover) {
';
                $buffer .= $indent . '    Popover.scan();
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
