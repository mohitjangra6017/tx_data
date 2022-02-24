<?php

class __Mustache_d813be62f1f8163b3f665d6589f53059 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        // 'logourl' section
        $value = $context->find('logourl');
        $buffer .= $this->section650984a520e4f5c00519116030bab7dd($context, $indent, $value);

        return $buffer;
    }

    private function sectionEc76a3215c097908dae6254d16d7e43f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'alt="{{.}}"';
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
                
                $buffer .= 'alt="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section650984a520e4f5c00519116030bab7dd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
<div class="masthead_logo">
    <h1 class="masthead_logo--header">
        <a class="masthead_logo--header_link" href="{{siteurl}}">
            <img class="masthead_logo--header_img" src="{{logourl}}" {{#logoalt}}alt="{{.}}"{{/logoalt}} />
            <span class="sr-only">{{shortname}}</span>
        </a>
    </h1>
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
                
                $buffer .= $indent . '<div class="masthead_logo">
';
                $buffer .= $indent . '    <h1 class="masthead_logo--header">
';
                $buffer .= $indent . '        <a class="masthead_logo--header_link" href="';
                $value = $this->resolveValue($context->find('siteurl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $buffer .= $indent . '            <img class="masthead_logo--header_img" src="';
                $value = $this->resolveValue($context->find('logourl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" ';
                // 'logoalt' section
                $value = $context->find('logoalt');
                $buffer .= $this->sectionEc76a3215c097908dae6254d16d7e43f($context, $indent, $value);
                $buffer .= ' />
';
                $buffer .= $indent . '            <span class="sr-only">';
                $value = $this->resolveValue($context->find('shortname'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</span>
';
                $buffer .= $indent . '        </a>
';
                $buffer .= $indent . '    </h1>
';
                $buffer .= $indent . '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
