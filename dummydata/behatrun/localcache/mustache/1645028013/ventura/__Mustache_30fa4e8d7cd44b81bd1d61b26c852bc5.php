<?php

class __Mustache_30fa4e8d7cd44b81bd1d61b26c852bc5 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'core/chooser';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context);
        }
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->section95f7c3b871912d2faa1cb6e7d48d4c7a($context, $indent, $value);

        return $buffer;
    }

    private function section95f7c3b871912d2faa1cb6e7d48d4c7a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([
    \'core/yui\',
    \'core/str\'
], function(Y, Str) {
    Str.get_strings([
        { key: \'addresourceoractivity\', component: \'moodle\' },
        { key: \'close\', component: \'editor\' },
    ]).then(function(add, close) {
        Y.use(\'moodle-course-modchooser\', function() {
            M.course.init_chooser({
                courseid: {{courseid}},
                closeButtonTitle: close
            });
        });
    });
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
                
                $buffer .= $indent . 'require([
';
                $buffer .= $indent . '    \'core/yui\',
';
                $buffer .= $indent . '    \'core/str\'
';
                $buffer .= $indent . '], function(Y, Str) {
';
                $buffer .= $indent . '    Str.get_strings([
';
                $buffer .= $indent . '        { key: \'addresourceoractivity\', component: \'moodle\' },
';
                $buffer .= $indent . '        { key: \'close\', component: \'editor\' },
';
                $buffer .= $indent . '    ]).then(function(add, close) {
';
                $buffer .= $indent . '        Y.use(\'moodle-course-modchooser\', function() {
';
                $buffer .= $indent . '            M.course.init_chooser({
';
                $buffer .= $indent . '                courseid: ';
                $value = $this->resolveValue($context->find('courseid'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ',
';
                $buffer .= $indent . '                closeButtonTitle: close
';
                $buffer .= $indent . '            });
';
                $buffer .= $indent . '        });
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
