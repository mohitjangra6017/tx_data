<?php

class __Mustache_322583d7ef2cd1fc784f7e47de5796a2 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div id="fitem_';
        $value = $this->resolveValue($context->findDot('element.id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" class="fitem fitem_';
        $value = $this->resolveValue($context->findDot('element.type'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" ';
        // 'element.groupname' section
        $value = $context->findDot('element.groupname');
        $buffer .= $this->sectionE32dd8819580c2040e2441120199ff04($context, $indent, $value);
        $buffer .= '>
';
        $buffer .= $indent . '    <div class="fitemtitle">
';
        $buffer .= $indent . '      <label for="';
        $value = $this->resolveValue($context->findDot('element.id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">';
        $value = $this->resolveValue($context->find('label'), $context);
        $buffer .= $value;
        // 'required' section
        $value = $context->find('required');
        $buffer .= $this->sectionC039e43ca241856ffae9744cb278eecb($context, $indent, $value);
        $buffer .= '</label>
';
        $buffer .= $indent . '        ';
        $value = $this->resolveValue($context->find('helpbutton'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    <div class="felement ';
        // 'error' section
        $value = $context->find('error');
        $buffer .= $this->section4aa3c215f25f5c79c1eb5e2978945f35($context, $indent, $value);
        $buffer .= ' f';
        $value = $this->resolveValue($context->findDot('element.type'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        // 'error' section
        $value = $context->find('error');
        $buffer .= $this->section38f99e041a36ac5be908a9b80f8ed401($context, $indent, $value);
        $blockFunction = $context->findInBlock('element');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        } else {
            $buffer .= $indent . '            <!-- Element goes here -->
';
        }
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionE32dd8819580c2040e2441120199ff04(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'data-groupname="{{.}}"';
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
                
                $buffer .= 'data-groupname="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1f72f4ddc9235cc3e9bd8d7d79839141(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'required,requiredelement,form,form-required';
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
                
                $buffer .= 'required,requiredelement,form,form-required';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC039e43ca241856ffae9744cb278eecb(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#flex_icon}}required,requiredelement,form,form-required{{/flex_icon}}';
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
                $buffer .= $this->section1f72f4ddc9235cc3e9bd8d7d79839141($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4aa3c215f25f5c79c1eb5e2978945f35(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' error';
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
                
                $buffer .= ' error';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section38f99e041a36ac5be908a9b80f8ed401(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <span class="error" id="id_error_{{ element.name }}">
            {{{ error }}}
        </span>
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
                
                $buffer .= $indent . '        <span class="error" id="id_error_';
                $value = $this->resolveValue($context->findDot('element.name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $buffer .= $indent . '            ';
                $value = $this->resolveValue($context->find('error'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '        </span>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
