<?php

class __Mustache_76cfc6cc92f5ae23e0c55e3f1f393506 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="feedback_info">
';
        $buffer .= $indent . '    <span
';
        $buffer .= $indent . '        class="feedback_info">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section2e9c5f34b5a038195a78fded71338f9c($context, $indent, $value);
        $buffer .= ': </span><span
';
        $buffer .= $indent . '        class="feedback_info_value">';
        $value = $this->resolveValue($context->find('completedcount'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '</span>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="feedback_info">
';
        $buffer .= $indent . '    <span
';
        $buffer .= $indent . '        class="feedback_info">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section59c606871183481f30b22d5204ca9d5d($context, $indent, $value);
        $buffer .= ': </span><span
';
        $buffer .= $indent . '        class="feedback_info_value">';
        $value = $this->resolveValue($context->find('itemscount'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '</span>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        // 'timeopen' section
        $value = $context->find('timeopen');
        $buffer .= $this->sectionA4f3471a9d201bddca2b3ac3c0a9f608($context, $indent, $value);
        $buffer .= $indent . '
';
        // 'timeclose' section
        $value = $context->find('timeclose');
        $buffer .= $this->sectionDdf2fddf4fd818705c304b5febe8778b($context, $indent, $value);
        $buffer .= $indent . '
';

        return $buffer;
    }

    private function section2e9c5f34b5a038195a78fded71338f9c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'completed_feedbacks, mod_feedback';
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
                
                $buffer .= 'completed_feedbacks, mod_feedback';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section59c606871183481f30b22d5204ca9d5d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'questions, mod_feedback';
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
                
                $buffer .= 'questions, mod_feedback';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA2cdab93c4798399041431e334fa2903(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'feedbackopen, mod_feedback';
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
                
                $buffer .= 'feedbackopen, mod_feedback';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA4f3471a9d201bddca2b3ac3c0a9f608(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="feedback_info">
        <span
            class="feedback_info">{{#str}}feedbackopen, mod_feedback{{/str}}: </span><span
            class="feedback_info_value">{{timeopen}}</span>
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
                
                $buffer .= $indent . '    <div class="feedback_info">
';
                $buffer .= $indent . '        <span
';
                $buffer .= $indent . '            class="feedback_info">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionA2cdab93c4798399041431e334fa2903($context, $indent, $value);
                $buffer .= ': </span><span
';
                $buffer .= $indent . '            class="feedback_info_value">';
                $value = $this->resolveValue($context->find('timeopen'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</span>
';
                $buffer .= $indent . '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6f74adf802abffd7e58c1ab0d8846b72(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'feedbackclose, mod_feedback';
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
                
                $buffer .= 'feedbackclose, mod_feedback';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionDdf2fddf4fd818705c304b5febe8778b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="feedback_info">
        <span
            class="feedback_info">{{#str}}feedbackclose, mod_feedback{{/str}}: </span><span
            class="feedback_info_value">{{timeclose}}</span>
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
                
                $buffer .= $indent . '    <div class="feedback_info">
';
                $buffer .= $indent . '        <span
';
                $buffer .= $indent . '            class="feedback_info">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section6f74adf802abffd7e58c1ab0d8846b72($context, $indent, $value);
                $buffer .= ': </span><span
';
                $buffer .= $indent . '            class="feedback_info_value">';
                $value = $this->resolveValue($context->find('timeclose'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</span>
';
                $buffer .= $indent . '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
