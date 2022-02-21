<?php

class __Mustache_4a917d1b76900ef7955ba39eb4daf813 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="totaraNav">
';
        // 'masthead_menu' section
        $value = $context->find('masthead_menu');
        $buffer .= $this->section2bb40fc0500fe14210285630edb04ef0($context, $indent, $value);
        $buffer .= $indent . '</div>';

        return $buffer;
    }

    private function section3e23d4cbb0ca84f637cdd895b0ceb964(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> totara_core/nav_sub }}';
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
                $partialstr = 'totara_core/nav_sub';
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

    private function section2b4accaad2963a470301be0c4e0c7e88(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            require([\'totara_core/totaranav\'], function(totaraNav) {
                totaraNav.init(document.querySelector(\'.totaraNav\'));
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
                
                $buffer .= $indent . '            require([\'totara_core/totaranav\'], function(totaraNav) {
';
                $buffer .= $indent . '                totaraNav.init(document.querySelector(\'.totaraNav\'));
';
                $buffer .= $indent . '            });
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section2bb40fc0500fe14210285630edb04ef0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        {{> totara_core/nav_primary }}
        {{#subnav_has_items}}{{> totara_core/nav_sub }}{{/subnav_has_items}}
        {{#js}}
            require([\'totara_core/totaranav\'], function(totaraNav) {
                totaraNav.init(document.querySelector(\'.totaraNav\'));
            });
        {{/js}}
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
                $partialstr = 'totara_core/nav_primary';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context, $indent . '        ');
                }
                $buffer .= $indent . '        ';
                // 'subnav_has_items' section
                $value = $context->find('subnav_has_items');
                $buffer .= $this->section3e23d4cbb0ca84f637cdd895b0ceb964($context, $indent, $value);
                $buffer .= '
';
                // 'js' section
                $value = $context->find('js');
                $buffer .= $this->section2b4accaad2963a470301be0c4e0c7e88($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
