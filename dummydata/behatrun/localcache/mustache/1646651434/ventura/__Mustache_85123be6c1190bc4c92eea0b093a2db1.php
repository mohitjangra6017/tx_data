<?php

class __Mustache_85123be6c1190bc4c92eea0b093a2db1 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div>
';
        $buffer .= $indent . '<textarea id="';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" name="';
        $value = $this->resolveValue($context->find('name'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '[text]" rows="';
        $value = $this->resolveValue($context->find('rows'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" cols="';
        $value = $this->resolveValue($context->find('cols'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" spellcheck="true" ';
        // 'changelistener' section
        $value = $context->find('changelistener');
        $buffer .= $this->section2676ebe6b7d528c66077b20393fe6b0c($context, $indent, $value);
        $buffer .= '>';
        $value = $this->resolveValue($context->find('value'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '</textarea>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '<div>
';
        // 'hasformats' section
        $value = $context->find('hasformats');
        $buffer .= $this->section96f832b145fd06b495340d057b8ba974($context, $indent, $value);
        // 'hasformats' inverted section
        $value = $context->find('hasformats');
        if (empty($value)) {
            
            $buffer .= $indent . '        <input name="';
            $value = $this->resolveValue($context->find('name'), $context);
            $buffer .= call_user_func($this->mustache->getEscape(), $value);
            $buffer .= '[format]" type="hidden" value="';
            $value = $this->resolveValue($context->find('format'), $context);
            $buffer .= call_user_func($this->mustache->getEscape(), $value);
            $buffer .= '"/>
';
        }
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function section2676ebe6b7d528c66077b20393fe6b0c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' onblur="{{onblur}}"
    onchange="{{onchange}}" ';
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
                
                $buffer .= ' onblur="';
                $value = $this->resolveValue($context->find('onblur'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '    onchange="';
                $value = $this->resolveValue($context->find('onchange'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9e2875c627d2dbad7c957250bbb623f7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'selected';
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
                
                $buffer .= 'selected';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6f8497075ef904860a0f20af4397f35f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <option value="{{value}}" {{#selected}}selected{{/selected}}>{{text}}</option>
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
                
                $buffer .= $indent . '            <option value="';
                $value = $this->resolveValue($context->find('value'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" ';
                // 'selected' section
                $value = $context->find('selected');
                $buffer .= $this->section9e2875c627d2dbad7c957250bbb623f7($context, $indent, $value);
                $buffer .= '>';
                $value = $this->resolveValue($context->find('text'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</option>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section96f832b145fd06b495340d057b8ba974(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <select name="{{name}}[format]" id="menu{{name}}format">
        {{#formats}}
            <option value="{{value}}" {{#selected}}selected{{/selected}}>{{text}}</option>
        {{/formats}}
        </select>
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
                
                $buffer .= $indent . '        <select name="';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '[format]" id="menu';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= 'format">
';
                // 'formats' section
                $value = $context->find('formats');
                $buffer .= $this->section6f8497075ef904860a0f20af4397f35f($context, $indent, $value);
                $buffer .= $indent . '        </select>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
