<?php

class __Mustache_e8e132c257da51eef65154d6159699dc extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="mod_facetoface__sessions mod_facetoface__sessions--';
        $value = $this->resolveValue($context->find('type'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $buffer .= $indent . '    <div class="mod_facetoface__sessions__spinner" role="presentation">';
        // 'flex_icon' section
        $value = $context->find('flex_icon');
        $buffer .= $this->section201a1e0d87e2bf754decb79303412b27($context, $indent, $value);
        $buffer .= '</div>
';
        // 'debug' section
        $value = $context->find('debug');
        $buffer .= $this->sectionD34b7f819778cb7922cc6750ea483a5a($context, $indent, $value);
        // 'reservation' section
        $value = $context->find('reservation');
        $buffer .= $this->section263b5ffa6486bbbfa6bda0502dc10fa8($context, $indent, $value);
        // 'pastlink' section
        $value = $context->find('pastlink');
        $buffer .= $this->section927b294cfb9b1767b0536c1e1b30701d($context, $indent, $value);
        // 'table' inverted section
        $value = $context->find('table');
        if (empty($value)) {
            
            $buffer .= $indent . '        <div class="mod_facetoface__sessions__empty mod_facetoface__sessionlist--empty">';
            // 'str' section
            $value = $context->find('str');
            $buffer .= $this->section5af69ceb209a52104e2c2dedee2532bd($context, $indent, $value);
            $buffer .= '</div>
';
        }
        // 'table' section
        $value = $context->find('table');
        $buffer .= $this->sectionFaef2ca7f974342216648e861c00657a($context, $indent, $value);
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function section201a1e0d87e2bf754decb79303412b27(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' loading ';
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
                
                $buffer .= ' loading ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD34b7f819778cb7922cc6750ea483a5a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="mod_facetoface__sessions__debug" role="presentation">{{{.}}}</div>
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
                
                $buffer .= $indent . '        <div class="mod_facetoface__sessions__debug" role="presentation">';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= $value;
                $buffer .= '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section263b5ffa6486bbbfa6bda0502dc10fa8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="mod_facetoface__sessionlist__reservation">{{{.}}}</div>
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
                
                $buffer .= $indent . '        <div class="mod_facetoface__sessionlist__reservation">';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= $value;
                $buffer .= '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section36d0242880fd41b624218ba4c8470eb3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> &&template }}';
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
                $partialstr = '&&template';
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

    private function section927b294cfb9b1767b0536c1e1b30701d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="mod_facetoface__sessionlist__pastlink">{{#context}}{{> &&template }}{{/context}}</div>
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
                
                $buffer .= $indent . '        <div class="mod_facetoface__sessionlist__pastlink">';
                // 'context' section
                $value = $context->find('context');
                $buffer .= $this->section36d0242880fd41b624218ba4c8470eb3($context, $indent, $value);
                $buffer .= '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5af69ceb209a52104e2c2dedee2532bd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' noresults, mod_facetoface ';
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
                
                $buffer .= ' noresults, mod_facetoface ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFaef2ca7f974342216648e861c00657a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="mod_facetoface__sessionlist {{legacystateclass}}">{{#context}}{{> &&template }}{{/context}}</div>
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
                
                $buffer .= $indent . '        <div class="mod_facetoface__sessionlist ';
                $value = $this->resolveValue($context->find('legacystateclass'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                // 'context' section
                $value = $context->find('context');
                $buffer .= $this->section36d0242880fd41b624218ba4c8470eb3($context, $indent, $value);
                $buffer .= '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
