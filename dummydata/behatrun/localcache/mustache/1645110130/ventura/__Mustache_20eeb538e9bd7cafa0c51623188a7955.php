<?php

class __Mustache_20eeb538e9bd7cafa0c51623188a7955 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div data-region="grading-navigation-panel" data-first-userid="';
        $value = $this->resolveValue($context->find('userid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" data-courseid="';
        $value = $this->resolveValue($context->find('courseid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" data-showuseridentity="';
        $value = $this->resolveValue($context->find('showuseridentity'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'mod_assign/grading_navigation';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context);
        }
        $buffer .= $indent . '</div>
';
        // 'showreview' section
        $value = $context->find('showreview');
        $buffer .= $this->section59c60fb5bef6e510a33414fba685bf78($context, $indent, $value);
        $buffer .= $indent . '<div data-region="grade-panel" ';
        // 'showreview' inverted section
        $value = $context->find('showreview');
        if (empty($value)) {
            
            $buffer .= 'class="fullwidth"';
        }
        $buffer .= '>
';
        $buffer .= $indent . '<div data-region="grade" data-contextid="';
        $value = $this->resolveValue($context->find('contextid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" data-assignmentid="';
        $value = $this->resolveValue($context->find('assignmentid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'mod_assign/grading_panel';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context);
        }
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '<div data-region="grade-actions-panel">
';
        $buffer .= $indent . '<div data-region="grade-actions">
';
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'mod_assign/grading_actions';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context);
        }
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '<div data-region="overlay" class="moodle-has-zindex">
';
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'mod_assign/grading_save_in_progress';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context);
        }
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function section0883c5af6ad9f3950557bb23c6e1692b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' t/expanded, core, collapsereviewpanel, mod_assign ';
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
                
                $buffer .= ' t/expanded, core, collapsereviewpanel, mod_assign ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBf6c1cbec2824b40a8dbcc3d5a683207(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' t/collapsed, core, expandreviewpanel, mod_assign ';
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
                
                $buffer .= ' t/collapsed, core, expandreviewpanel, mod_assign ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section664a262e06d4bcee40b1e1c3bd2a41b3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' t/collapsed_rtl, core, expandreviewpanel, mod_assign ';
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
                
                $buffer .= ' t/collapsed_rtl, core, expandreviewpanel, mod_assign ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6abcfae36018e990de68d20a4efe493d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' clickexpandreviewpanel, mod_assign ';
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
                
                $buffer .= ' clickexpandreviewpanel, mod_assign ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section59c60fb5bef6e510a33414fba685bf78(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
<div data-region="review-panel">
    <a href="#" data-region="review-panel-toggle">
        <div class="collapse-icon">{{#pix}} t/expanded, core, collapsereviewpanel, mod_assign {{/pix}}</div>
        <div class="expand-icon">
            <div class="ltr-icon">{{#pix}} t/collapsed, core, expandreviewpanel, mod_assign {{/pix}}</div>
            <div class="rtl-icon">{{#pix}} t/collapsed_rtl, core, expandreviewpanel, mod_assign {{/pix}}</div>
            <div class="toggle-text">{{#str}} clickexpandreviewpanel, mod_assign {{/str}}</div>
        </div>
    </a>
    <div data-region="review-panel-content">
        <div data-region="review">
            {{> mod_assign/review_panel }}
        </div>
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
                
                $buffer .= $indent . '<div data-region="review-panel">
';
                $buffer .= $indent . '    <a href="#" data-region="review-panel-toggle">
';
                $buffer .= $indent . '        <div class="collapse-icon">';
                // 'pix' section
                $value = $context->find('pix');
                $buffer .= $this->section0883c5af6ad9f3950557bb23c6e1692b($context, $indent, $value);
                $buffer .= '</div>
';
                $buffer .= $indent . '        <div class="expand-icon">
';
                $buffer .= $indent . '            <div class="ltr-icon">';
                // 'pix' section
                $value = $context->find('pix');
                $buffer .= $this->sectionBf6c1cbec2824b40a8dbcc3d5a683207($context, $indent, $value);
                $buffer .= '</div>
';
                $buffer .= $indent . '            <div class="rtl-icon">';
                // 'pix' section
                $value = $context->find('pix');
                $buffer .= $this->section664a262e06d4bcee40b1e1c3bd2a41b3($context, $indent, $value);
                $buffer .= '</div>
';
                $buffer .= $indent . '            <div class="toggle-text">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section6abcfae36018e990de68d20a4efe493d($context, $indent, $value);
                $buffer .= '</div>
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '    </a>
';
                $buffer .= $indent . '    <div data-region="review-panel-content">
';
                $buffer .= $indent . '        <div data-region="review">
';
                // Totara hack: if the partial starts with "." then try
                // to resolve it from the current context.
                $partialstr = 'mod_assign/review_panel';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context, $indent . '            ');
                }
                $buffer .= $indent . '        </div>
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

}
