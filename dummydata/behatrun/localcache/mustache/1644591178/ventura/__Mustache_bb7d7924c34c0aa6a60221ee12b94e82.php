<?php

class __Mustache_bb7d7924c34c0aa6a60221ee12b94e82 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        if ($parent = $this->mustache->loadPartial('core_form/element-template')) {
            $context->pushBlockContext(array(
                'element' => array($this, 'block45c9ce4e7a5eaf59f578b3c0034d6c78'),
            ));
            $buffer .= $parent->renderInternal($context, $indent);
            $context->popBlockContext();
        }
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->section8454ba76b22dc4b75fc5582cf255f362($context, $indent, $value);

        return $buffer;
    }

    private function section0d273d135e843aa4fe45a7ff910fc4a7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'readonly data-';
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
                
                $buffer .= 'readonly data-';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section89974e7b8a3f8b3e073daf462fa50f2c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'unmaskpassword,form';
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
                
                $buffer .= 'unmaskpassword,form';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8454ba76b22dc4b75fc5582cf255f362(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'core_form/passwordunmask\'], function(PasswordUnmask) {
    new PasswordUnmask("{{ element.id }}");
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
                
                $buffer .= $indent . 'require([\'core_form/passwordunmask\'], function(PasswordUnmask) {
';
                $buffer .= $indent . '    new PasswordUnmask("';
                $value = $this->resolveValue($context->findDot('element.id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '");
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    public function block45c9ce4e7a5eaf59f578b3c0034d6c78($context)
    {
        $indent = $buffer = '';
        $buffer .= $indent . '        <div class="totara_passwordunmask">
';
        $buffer .= $indent . '            <div class="wrap"><input type="text" id="';
        $value = $this->resolveValue($context->findDot('element.id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" ';
        // 'element.frozen' section
        $value = $context->findDot('element.frozen');
        $buffer .= $this->section0d273d135e843aa4fe45a7ff910fc4a7($context, $indent, $value);
        $buffer .= 'name="';
        $value = $this->resolveValue($context->findDot('element.name'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" value="';
        $value = $this->resolveValue($context->findDot('element.value'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" autocomplete="off" spellcheck="false" autocapitalize="none" autocorrect="off" class="passwordinput" /></div>
';
        $buffer .= $indent . '            <div class="unmask-password-option"><input id="';
        $value = $this->resolveValue($context->findDot('element.id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= 'unmask" type="checkbox"><label for="';
        $value = $this->resolveValue($context->findDot('element.id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= 'unmask">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section89974e7b8a3f8b3e073daf462fa50f2c($context, $indent, $value);
        $buffer .= '</label></div>
';
        $buffer .= $indent . '        </div>
';
    
        return $buffer;
    }
}
