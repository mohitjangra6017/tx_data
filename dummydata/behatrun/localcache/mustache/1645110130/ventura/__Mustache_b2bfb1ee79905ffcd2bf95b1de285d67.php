<?php

class __Mustache_b2bfb1ee79905ffcd2bf95b1de285d67 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<form data-region="grading-actions-form" class="hide">
';
        $buffer .= $indent . '    <label>';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->sectionE4bbab7d2adabb93d1d7bc2bc143a83a($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '           <input type="checkbox" name="sendstudentnotifications"
';
        $buffer .= $indent . '                  ';
        // 'defaultsendnotifications' section
        $value = $context->find('defaultsendnotifications');
        $buffer .= $this->sectionE6c044fe8710d3502dd5cb9686c32f3f($context, $indent, $value);
        $buffer .= ' />
';
        $buffer .= $indent . '    </label>
';
        $buffer .= $indent . '    <button type="submit" class="btn btn-primary" name="savechanges">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->sectionCc896fb1429559fad42f2607525c3e3c($context, $indent, $value);
        $buffer .= '</button>
';
        $buffer .= $indent . '    <button type="submit" class="btn btn-default" name="resetbutton">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section65bdb401750b97914f5899115f865e4d($context, $indent, $value);
        $buffer .= '</button>
';
        $buffer .= $indent . '</form>
';
        // 'showreview' section
        $value = $context->find('showreview');
        $buffer .= $this->section518a9390bfe6796e1569e0b87fa45df4($context, $indent, $value);
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->section0f096988a0f8b091c0ee6a97c6f031ac($context, $indent, $value);

        return $buffer;
    }

    private function sectionE4bbab7d2adabb93d1d7bc2bc143a83a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'sendstudentnotifications, mod_assign';
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
                
                $buffer .= 'sendstudentnotifications, mod_assign';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE6c044fe8710d3502dd5cb9686c32f3f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'checked="checked"';
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
                
                $buffer .= 'checked="checked"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionCc896fb1429559fad42f2607525c3e3c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'savechanges';
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
                
                $buffer .= 'savechanges';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section65bdb401750b97914f5899115f865e4d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'reset';
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
                
                $buffer .= 'reset';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8dbe3d21ae2a8ec348fe4c4396a23964(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' layout-expand-right, mod_assign, collapsereviewpanel, mod_assign ';
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
                
                $buffer .= ' layout-expand-right, mod_assign, collapsereviewpanel, mod_assign ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8d852e7e0ebf2d8dd15847b6de94bf7f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' layout-default, mod_assign, defaultlayout, mod_assign ';
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
                
                $buffer .= ' layout-default, mod_assign, defaultlayout, mod_assign ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0a7be2f9c425369b466279ff6e633298(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' layout-expand-left, mod_assign, collapsegradepanel, mod_assign ';
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
                
                $buffer .= ' layout-expand-left, mod_assign, collapsegradepanel, mod_assign ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section518a9390bfe6796e1569e0b87fa45df4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
<div class="btn-toolbar collapse-buttons">
    <div class="btn-group">
        <button class="btn collapse-review-panel">{{#pix}} layout-expand-right, mod_assign, collapsereviewpanel, mod_assign {{/pix}}</button>
        <button class="btn collapse-none active">{{#pix}} layout-default, mod_assign, defaultlayout, mod_assign {{/pix}}</button>
        <button class="btn collapse-grade-panel">{{#pix}} layout-expand-left, mod_assign, collapsegradepanel, mod_assign {{/pix}}</button>
    </div>
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
                
                $buffer .= $indent . '<div class="btn-toolbar collapse-buttons">
';
                $buffer .= $indent . '    <div class="btn-group">
';
                $buffer .= $indent . '        <button class="btn collapse-review-panel">';
                // 'pix' section
                $value = $context->find('pix');
                $buffer .= $this->section8dbe3d21ae2a8ec348fe4c4396a23964($context, $indent, $value);
                $buffer .= '</button>
';
                $buffer .= $indent . '        <button class="btn collapse-none active">';
                // 'pix' section
                $value = $context->find('pix');
                $buffer .= $this->section8d852e7e0ebf2d8dd15847b6de94bf7f($context, $indent, $value);
                $buffer .= '</button>
';
                $buffer .= $indent . '        <button class="btn collapse-grade-panel">';
                // 'pix' section
                $value = $context->find('pix');
                $buffer .= $this->section0a7be2f9c425369b466279ff6e633298($context, $indent, $value);
                $buffer .= '</button>
';
                $buffer .= $indent . '    </div>
';
                $buffer .= $indent . '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0f096988a0f8b091c0ee6a97c6f031ac(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'mod_assign/grading_actions\'], function(GradingActions) {
    new GradingActions(\'[data-region="grade-actions"]\');
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
                
                $buffer .= $indent . 'require([\'mod_assign/grading_actions\'], function(GradingActions) {
';
                $buffer .= $indent . '    new GradingActions(\'[data-region="grade-actions"]\');
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
