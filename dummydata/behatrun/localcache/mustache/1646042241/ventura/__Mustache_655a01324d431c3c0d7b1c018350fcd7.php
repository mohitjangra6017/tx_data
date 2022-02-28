<?php

class __Mustache_655a01324d431c3c0d7b1c018350fcd7 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="block_last_course_accessed_content">
';
        $buffer .= $indent . '    <p class="course_name_';
        $value = $this->resolveValue($context->find('course_name_class'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"><a href="';
        $value = $this->resolveValue($context->find('course_url'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" title="';
        $value = $this->resolveValue($context->find('course_name_link_title'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">';
        $value = $this->resolveValue($context->find('course_name'), $context);
        $buffer .= $value;
        $buffer .= '</a></p>
';
        // 'progress' section
        $value = $context->find('progress');
        $buffer .= $this->section6a30bb3d02bc024d28cb20362115cfde($context, $indent, $value);
        $buffer .= $indent . '</div>';

        return $buffer;
    }

    private function section56fcdf601d73edf7ce7be06fc4f5a76a(Mustache_Context $context, $indent, $value)
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
                    $buffer .= $partial->renderInternal($context, $indent . '            ');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6a30bb3d02bc024d28cb20362115cfde(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        {{#pbar}}
            {{> core/progress_bar }}
        {{/pbar}}
        {{^pbar}}
            <div class="progressbar_container">
                <p class="label label-default">{{statustext}}</p>
            </div>
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
                $buffer .= $this->section56fcdf601d73edf7ce7be06fc4f5a76a($context, $indent, $value);
                // 'pbar' inverted section
                $value = $context->find('pbar');
                if (empty($value)) {
                    
                    $buffer .= $indent . '            <div class="progressbar_container">
';
                    $buffer .= $indent . '                <p class="label label-default">';
                    $value = $this->resolveValue($context->find('statustext'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '</p>
';
                    $buffer .= $indent . '            </div>
';
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
