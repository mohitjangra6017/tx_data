<?php

class __Mustache_02479d47e7d8b2e06611e34d5a58a57c extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'toolbars_has_items' section
        $value = $context->find('toolbars_has_items');
        $buffer .= $this->sectionCfdcf1189cb74d85c09b0dff7c34d268($context, $indent, $value);

        return $buffer;
    }

    private function section30bc0a0db06cbdaf6cdfb88137e30ce3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="toolbar-cell">
                {{{.}}}
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
                
                $buffer .= $indent . '            <div class="toolbar-cell">
';
                $buffer .= $indent . '                ';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '            </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5fe9aa7f5b161d91f7368c22ec23ab6d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="toolbar-left-table">
            {{#left_content}}
            <div class="toolbar-cell">
                {{{.}}}
            </div>
            {{/left_content}}
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
                
                $buffer .= $indent . '        <div class="toolbar-left-table">
';
                // 'left_content' section
                $value = $context->find('left_content');
                $buffer .= $this->section30bc0a0db06cbdaf6cdfb88137e30ce3($context, $indent, $value);
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section373ba80651ed466ab6d55c9cb4010838(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <div class="toolbar-cell">
                        {{{.}}}
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
                
                $buffer .= $indent . '                    <div class="toolbar-cell">
';
                $buffer .= $indent . '                        ';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '                    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA205a7c31a737b6e56b2a6c47313d749(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="toolbar-right-table">
                {{#right_content}}
                    <div class="toolbar-cell">
                        {{{.}}}
                    </div>
                {{/right_content}}
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
                
                $buffer .= $indent . '            <div class="toolbar-right-table">
';
                // 'right_content' section
                $value = $context->find('right_content');
                $buffer .= $this->section373ba80651ed466ab6d55c9cb4010838($context, $indent, $value);
                $buffer .= $indent . '            </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section25fe07e344abc449881144d82575794c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        {{#left_content_has_items}}
        <div class="toolbar-left-table">
            {{#left_content}}
            <div class="toolbar-cell">
                {{{.}}}
            </div>
            {{/left_content}}
        </div>
        {{/left_content_has_items}}
        {{#right_content_has_items}}
            <div class="toolbar-right-table">
                {{#right_content}}
                    <div class="toolbar-cell">
                        {{{.}}}
                    </div>
                {{/right_content}}
            </div>
        {{/right_content_has_items}}
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
                
                // 'left_content_has_items' section
                $value = $context->find('left_content_has_items');
                $buffer .= $this->section5fe9aa7f5b161d91f7368c22ec23ab6d($context, $indent, $value);
                // 'right_content_has_items' section
                $value = $context->find('right_content_has_items');
                $buffer .= $this->sectionA205a7c31a737b6e56b2a6c47313d749($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionCfdcf1189cb74d85c09b0dff7c34d268(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
<div class="totara-toolbar totara-toolbar-{{postion}}">
    {{#toolbars}}
        {{#left_content_has_items}}
        <div class="toolbar-left-table">
            {{#left_content}}
            <div class="toolbar-cell">
                {{{.}}}
            </div>
            {{/left_content}}
        </div>
        {{/left_content_has_items}}
        {{#right_content_has_items}}
            <div class="toolbar-right-table">
                {{#right_content}}
                    <div class="toolbar-cell">
                        {{{.}}}
                    </div>
                {{/right_content}}
            </div>
        {{/right_content_has_items}}
    {{/toolbars}}
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
                
                $buffer .= $indent . '<div class="totara-toolbar totara-toolbar-';
                $value = $this->resolveValue($context->find('postion'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                // 'toolbars' section
                $value = $context->find('toolbars');
                $buffer .= $this->section25fe07e344abc449881144d82575794c($context, $indent, $value);
                $buffer .= $indent . '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
