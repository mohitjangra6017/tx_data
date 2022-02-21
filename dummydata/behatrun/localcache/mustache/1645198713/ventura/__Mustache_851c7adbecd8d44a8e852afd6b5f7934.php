<?php

class __Mustache_851c7adbecd8d44a8e852afd6b5f7934 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="';
        $value = $this->resolveValue($context->find('classes'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"';
        // 'attributes' section
        $value = $context->find('attributes');
        $buffer .= $this->sectionAd20463c348991d5bbd2fb97358ea7c0($context, $indent, $value);
        $buffer .= ' ';
        // 'loader' section
        $value = $context->find('loader');
        $buffer .= $this->section648f16268bd4f9365bd3417d377070df($context, $indent, $value);
        $buffer .= '>
';
        // 'primary' section
        $value = $context->find('primary');
        $buffer .= $this->section68379151c399a5e9de40598075b3c76a($context, $indent, $value);
        $buffer .= $indent . '
';
        // 'secondary' section
        $value = $context->find('secondary');
        $buffer .= $this->section4f857a96921a25ea23e8f4fc057a6b15($context, $indent, $value);
        $buffer .= $indent . '</div>
';
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->section3795f65ed10c65135191c5ba78f5c3b6($context, $indent, $value);

        return $buffer;
    }

    private function sectionAd20463c348991d5bbd2fb97358ea7c0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' {{name}}="{{value}}"';
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
                
                $buffer .= ' ';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '="';
                $value = $this->resolveValue($context->find('value'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section648f16268bd4f9365bd3417d377070df(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'data-loader-url="{{url}}" data-loader-params="{{params}}"';
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
                
                $buffer .= 'data-loader-url="';
                $value = $this->resolveValue($context->find('url'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" data-loader-params="';
                $value = $this->resolveValue($context->find('params'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8597580726f78320d0e398ab287528e4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<li role="presentation">{{> core/action_menu_trigger }}</li>';
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
                
                $buffer .= '<li role="presentation">';
                // Totara hack: if the partial starts with "." then try
                // to resolve it from the current context.
                $partialstr = 'core/action_menu_trigger';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context);
                }
                $buffer .= '</li>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section732be9d267aa1bccceec5952e0e9aece(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<li role="presentation">{{> core/action_menu_item }}</li>';
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
                
                $buffer .= '<li role="presentation">';
                // Totara hack: if the partial starts with "." then try
                // to resolve it from the current context.
                $partialstr = 'core/action_menu_item';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context);
                }
                $buffer .= '</li>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section68379151c399a5e9de40598075b3c76a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '

        <ul class="{{classes}}"{{#attributes}} {{name}}="{{value}}"{{/attributes}}>

            {{#prioritise}}<li role="presentation">{{> core/action_menu_trigger }}</li>{{/prioritise}}<!--

            -->{{#items}}<li role="presentation">{{> core/action_menu_item }}</li>{{/items}}<!--

            -->{{^prioritise}}<li role="presentation">{{> core/action_menu_trigger }}</li>{{/prioritise}}

        </ul>

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
                
                $buffer .= $indent . '
';
                $buffer .= $indent . '        <ul class="';
                $value = $this->resolveValue($context->find('classes'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                // 'attributes' section
                $value = $context->find('attributes');
                $buffer .= $this->sectionAd20463c348991d5bbd2fb97358ea7c0($context, $indent, $value);
                $buffer .= '>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '            ';
                // 'prioritise' section
                $value = $context->find('prioritise');
                $buffer .= $this->section8597580726f78320d0e398ab287528e4($context, $indent, $value);
                $buffer .= '<!--
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '            -->';
                // 'items' section
                $value = $context->find('items');
                $buffer .= $this->section732be9d267aa1bccceec5952e0e9aece($context, $indent, $value);
                $buffer .= '<!--
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '            -->';
                // 'prioritise' inverted section
                $value = $context->find('prioritise');
                if (empty($value)) {
                    
                    $buffer .= '<li role="presentation">';
                    // Totara hack: if the partial starts with "." then try
                    // to resolve it from the current context.
                    $partialstr = 'core/action_menu_trigger';
                    if (strpos($partialstr, '&&') === 0) {
                        $partialstr = $context->find(substr($partialstr, 2));
                    }
                    if ($partial = $this->mustache->loadPartial($partialstr)) {
                        $buffer .= $partial->renderInternal($context);
                    }
                    $buffer .= '</li>';
                }
                $buffer .= '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        </ul>
';
                $buffer .= $indent . '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB1757c41fc32cd38d3028f6093a280de(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'loading';
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
                
                $buffer .= 'loading';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA0fdacb8fc65e1f41b407549b7f1a38b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<li role="presentation" class="spinner">{{#flex_icon}}loading{{/flex_icon}}</li>';
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
                
                $buffer .= '<li role="presentation" class="spinner">';
                // 'flex_icon' section
                $value = $context->find('flex_icon');
                $buffer .= $this->sectionB1757c41fc32cd38d3028f6093a280de($context, $indent, $value);
                $buffer .= '</li>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4f857a96921a25ea23e8f4fc057a6b15(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <ul class="{{classes}}"{{#attributes}} {{name}}="{{value}}"{{/attributes}}>
            {{#delayload}}<li role="presentation" class="spinner">{{#flex_icon}}loading{{/flex_icon}}</li>{{/delayload}}
            {{#items}}<li role="presentation">{{> core/action_menu_item }}</li>{{/items}}
        </ul>
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
                
                $buffer .= $indent . '        <ul class="';
                $value = $this->resolveValue($context->find('classes'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                // 'attributes' section
                $value = $context->find('attributes');
                $buffer .= $this->sectionAd20463c348991d5bbd2fb97358ea7c0($context, $indent, $value);
                $buffer .= '>
';
                $buffer .= $indent . '            ';
                // 'delayload' section
                $value = $context->find('delayload');
                $buffer .= $this->sectionA0fdacb8fc65e1f41b407549b7f1a38b($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '            ';
                // 'items' section
                $value = $context->find('items');
                $buffer .= $this->section732be9d267aa1bccceec5952e0e9aece($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '        </ul>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3795f65ed10c65135191c5ba78f5c3b6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'core/yui\'], function(Y) {
    Y.use(\'moodle-core-actionmenu\', function() {
        M.core.actionmenu.init();
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
                
                $buffer .= $indent . 'require([\'core/yui\'], function(Y) {
';
                $buffer .= $indent . '    Y.use(\'moodle-core-actionmenu\', function() {
';
                $buffer .= $indent . '        M.core.actionmenu.init();
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
