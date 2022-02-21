<?php

class __Mustache_2d50488f6c7c7c466cf799797c2af60e extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<li id="block_current_learning-course-';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" class="block_current_learning-item block_current_learning-course">
';
        $buffer .= $indent . '<div class="block_current_learning-row-item">
';
        $buffer .= $indent . '    <span data-toggle="tooltip" tabindex="0" data-placement="top" data-container="body" title="';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section2d47b9928a8f75d0a9b4c0f64306c662($context, $indent, $value);
        $buffer .= '">';
        // 'flex_icon' section
        $value = $context->find('flex_icon');
        $buffer .= $this->section16d1411ec28c903bce34ee87822f9c56($context, $indent, $value);
        $buffer .= '</span>
';
        $buffer .= $indent . '    <a href="';
        $value = $this->resolveValue($context->find('url_view'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"><h3>';
        $value = $this->resolveValue($context->find('fullname'), $context);
        $buffer .= $value;
        $buffer .= '</h3></a>
';
        $buffer .= $indent . '    <div class="block_current_learning-row-item__status">
';
        // 'dueinfo' section
        $value = $context->find('dueinfo');
        $buffer .= $this->sectionDd487fc7ba1a2c81435a4266e567764b($context, $indent, $value);
        // 'progress' section
        $value = $context->find('progress');
        $buffer .= $this->section97ce7dfcc26b004e07012ac43383ba93($context, $indent, $value);
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '</li>
';

        return $buffer;
    }

    private function section2d47b9928a8f75d0a9b4c0f64306c662(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' thisisacourse, block_current_learning ';
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
                
                $buffer .= ' thisisacourse, block_current_learning ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section16d1411ec28c903bce34ee87822f9c56(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'course';
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
                
                $buffer .= 'course';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionCd6bcccb0e2987045078d83692fb1d75(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'warning';
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
                
                $buffer .= 'warning';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section67d40f47705ac9adbc12b940915c4ccf(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#flex_icon}}warning{{/flex_icon}}';
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
                
                // 'flex_icon' section
                $value = $context->find('flex_icon');
                $buffer .= $this->sectionCd6bcccb0e2987045078d83692fb1d75($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionDd487fc7ba1a2c81435a4266e567764b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <span class="label {{state}}" tabindex="0" data-toggle="tooltip" data-placement="top" data-container="body" title="{{tooltip}}">{{#alert}}{{#flex_icon}}warning{{/flex_icon}}{{/alert}} {{duetext}}</span>
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
                
                $buffer .= $indent . '            <span class="label ';
                $value = $this->resolveValue($context->find('state'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" tabindex="0" data-toggle="tooltip" data-placement="top" data-container="body" title="';
                $value = $this->resolveValue($context->find('tooltip'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                // 'alert' section
                $value = $context->find('alert');
                $buffer .= $this->section67d40f47705ac9adbc12b940915c4ccf($context, $indent, $value);
                $buffer .= ' ';
                $value = $this->resolveValue($context->find('duetext'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</span>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA191afbffeeed39815552cc4bfdb42c6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
               {{> core/progress_bar }}
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
                $partialstr = 'core/progress_bar';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context, $indent . '               ');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section97ce7dfcc26b004e07012ac43383ba93(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{#pbar}}
               {{> core/progress_bar }}
            {{/pbar}}
            {{^pbar}}
                <span class="label label-default">{{summarytext}}</span>
            {{/pbar}}
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
                
                // 'pbar' section
                $value = $context->find('pbar');
                $buffer .= $this->sectionA191afbffeeed39815552cc4bfdb42c6($context, $indent, $value);
                // 'pbar' inverted section
                $value = $context->find('pbar');
                if (empty($value)) {
                    
                    $buffer .= $indent . '                <span class="label label-default">';
                    $value = $this->resolveValue($context->find('summarytext'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '</span>
';
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
