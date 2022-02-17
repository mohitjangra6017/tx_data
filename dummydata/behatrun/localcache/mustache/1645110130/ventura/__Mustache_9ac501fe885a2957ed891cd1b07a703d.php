<?php

class __Mustache_9ac501fe885a2957ed891cd1b07a703d extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'mod_assign/loading';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context);
        }
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->section210818b0f0cbbd73c0ed37a2c3f02816($context, $indent, $value);

        return $buffer;
    }

    private function section210818b0f0cbbd73c0ed37a2c3f02816(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'mod_assign/grading_navigation_user_info\'], function(UserInfo) {
    new UserInfo(\'[data-region="user-info"]\');
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
                
                $buffer .= $indent . 'require([\'mod_assign/grading_navigation_user_info\'], function(UserInfo) {
';
                $buffer .= $indent . '    new UserInfo(\'[data-region="user-info"]\');
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
