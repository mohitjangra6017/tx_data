<?php

class __Mustache_79e41ae4931a71f7e56acf61c9d8ad33 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="alert alert-info alert-with-icon ';
        // 'closebutton' section
        $value = $context->find('closebutton');
        $buffer .= $this->sectionFd90bec278b7256a499294048a301214($context, $indent, $value);
        $buffer .= ' fade in ';
        $value = $this->resolveValue($context->find('extraclasses'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" ';
        // 'announce' section
        $value = $context->find('announce');
        $buffer .= $this->section7173f970ca65fe2bb2d5277c1663f2da($context, $indent, $value);
        $buffer .= '>
';
        $buffer .= $indent . '    <div class="alert-icon">';
        // 'flex_icon' section
        $value = $context->find('flex_icon');
        $buffer .= $this->section091b273efe03c4bbb7a19896355e92b6($context, $indent, $value);
        $buffer .= '</div>
';
        $buffer .= $indent . '    <div class="alert-message">';
        $value = $this->resolveValue($context->find('message'), $context);
        $buffer .= $value;
        $buffer .= '</div>
';
        $buffer .= $indent . '    ';
        // 'closebutton' section
        $value = $context->find('closebutton');
        $buffer .= $this->section7cc9e5f79ede6f3ef0625144e31fb0cd($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '</div>
';
        // 'closebutton' section
        $value = $context->find('closebutton');
        $buffer .= $this->sectionFac0d26117d706b36ae83c0d28da5303($context, $indent, $value);

        return $buffer;
    }

    private function sectionFd90bec278b7256a499294048a301214(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'alert-dismissable';
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
                
                $buffer .= 'alert-dismissable';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7173f970ca65fe2bb2d5277c1663f2da(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' role="log"';
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
                
                $buffer .= ' role="log"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section091b273efe03c4bbb7a19896355e92b6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'core|notification-info, , , ft-size-200';
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
                
                $buffer .= 'core|notification-info, , , ft-size-200';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section10e72613a783a068380db2be7927e4b4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'closebuttontitle, core';
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
                
                $buffer .= 'closebuttontitle, core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionDb883623d51a0414807d90351344235e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'delete-ns, , , ft-size-200';
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
                
                $buffer .= 'delete-ns, , , ft-size-200';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7cc9e5f79ede6f3ef0625144e31fb0cd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<button type="button" class="close alert-close" data-dismiss="alert" aria-label="{{#str}}closebuttontitle, core{{/str}}">{{#flex_icon}}delete-ns, , , ft-size-200{{/flex_icon}}</button>';
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
                
                $buffer .= '<button type="button" class="close alert-close" data-dismiss="alert" aria-label="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section10e72613a783a068380db2be7927e4b4($context, $indent, $value);
                $buffer .= '">';
                // 'flex_icon' section
                $value = $context->find('flex_icon');
                $buffer .= $this->sectionDb883623d51a0414807d90351344235e($context, $indent, $value);
                $buffer .= '</button>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7007adc5b0e1c582c01881f5aa908d3e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    require([\'jquery\', \'theme_legacy/bootstrap\'], function($) {
        // Setup closing of bootstrap alerts.
        $().alert();
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
                
                $buffer .= $indent . '    require([\'jquery\', \'theme_legacy/bootstrap\'], function($) {
';
                $buffer .= $indent . '        // Setup closing of bootstrap alerts.
';
                $buffer .= $indent . '        $().alert();
';
                $buffer .= $indent . '    });
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFac0d26117d706b36ae83c0d28da5303(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    {{# js }}
    require([\'jquery\', \'theme_legacy/bootstrap\'], function($) {
        // Setup closing of bootstrap alerts.
        $().alert();
    });
    {{/ js }}
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
                
                // 'js' section
                $value = $context->find('js');
                $buffer .= $this->section7007adc5b0e1c582c01881f5aa908d3e($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
