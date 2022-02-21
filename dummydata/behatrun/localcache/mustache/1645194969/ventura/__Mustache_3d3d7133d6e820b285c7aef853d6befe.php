<?php

class __Mustache_3d3d7133d6e820b285c7aef853d6befe extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'accessible_skip' section
        $value = $context->find('accessible_skip');
        $buffer .= $this->sectionDbd37d56bb482c9d6c7db6064d3ec47e($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<div';
        // 'attributes' section
        $value = $context->find('attributes');
        $buffer .= $this->sectionAd20463c348991d5bbd2fb97358ea7c0($context, $indent, $value);
        $buffer .= '>
';
        $buffer .= $indent . '
';
        // 'header' section
        $value = $context->find('header');
        $buffer .= $this->section32e985cda9ff630151f0b469d9714703($context, $indent, $value);
        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="content block-content">
';
        $buffer .= $indent . '    ';
        $value = $this->resolveValue($context->find('content'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '
';
        // 'footer' section
        $value = $context->find('footer');
        $buffer .= $this->section5d18787911689fcb12073d629e347ecb($context, $indent, $value);
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        // 'annotation' section
        $value = $context->find('annotation');
        $buffer .= $this->section6bb1703479fff422252ff00e65badd69($context, $indent, $value);
        $buffer .= $indent . '
';
        // 'accessible_skip' section
        $value = $context->find('accessible_skip');
        $buffer .= $this->section2b9e9203587f2e846655e5eb10500e85($context, $indent, $value);
        $buffer .= '
';

        return $buffer;
    }

    private function sectionDbd37d56bb482c9d6c7db6064d3ec47e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' <a class="skip-block" href="#sb-{{id}}">{{skiptext}}</a> ';
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
                
                $buffer .= $indent . ' <a class="skip-block" href="#sb-';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                $value = $this->resolveValue($context->find('skiptext'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a> ';
                $context->pop();
            }
        }
    
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

    private function sectionF8a9d5476f8c06586e8ca2259cedf8d3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{{control_output}}}
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
                
                $buffer .= $indent . '            ';
                $value = $this->resolveValue($context->find('control_output'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section87b36fd25cdeb9dd86e3228b3c4b63dc(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'data-collapsible="1"';
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
                
                $buffer .= 'data-collapsible="1"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC110f35bb1298668e0bc65c8e43a41ff(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <h2 id="{{id}}" data-movetext="true">{{{text}}}</h2>
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
                
                $buffer .= $indent . '                <h2 id="';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" data-movetext="true">';
                $value = $this->resolveValue($context->find('text'), $context);
                $buffer .= $value;
                $buffer .= '</h2>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section32e985cda9ff630151f0b469d9714703(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{^no_header}}
    <div class="header block-header">
        {{#controls}}
            {{{control_output}}}
        {{/controls}}
        <div class="title block-title" data-dock-title="{{{dock_title}}}">
            <div class="block_action" {{#collapsible}}data-collapsible="1"{{/collapsible}}></div>
            {{#title}}
                <h2 id="{{id}}" data-movetext="true">{{{text}}}</h2>
            {{/title}}
        </div>
    </div>
{{/no_header}}';
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
                
                // 'no_header' inverted section
                $value = $context->find('no_header');
                if (empty($value)) {
                    
                    $buffer .= $indent . '    <div class="header block-header">
';
                    // 'controls' section
                    $value = $context->find('controls');
                    $buffer .= $this->sectionF8a9d5476f8c06586e8ca2259cedf8d3($context, $indent, $value);
                    $buffer .= $indent . '        <div class="title block-title" data-dock-title="';
                    $value = $this->resolveValue($context->find('dock_title'), $context);
                    $buffer .= $value;
                    $buffer .= '">
';
                    $buffer .= $indent . '            <div class="block_action" ';
                    // 'collapsible' section
                    $value = $context->find('collapsible');
                    $buffer .= $this->section87b36fd25cdeb9dd86e3228b3c4b63dc($context, $indent, $value);
                    $buffer .= '></div>
';
                    // 'title' section
                    $value = $context->find('title');
                    $buffer .= $this->sectionC110f35bb1298668e0bc65c8e43a41ff($context, $indent, $value);
                    $buffer .= $indent . '        </div>
';
                    $buffer .= $indent . '    </div>
';
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5d18787911689fcb12073d629e347ecb(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="footer">
            {{{footer_content}}}
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
                
                $buffer .= $indent . '        <div class="footer">
';
                $buffer .= $indent . '            ';
                $value = $this->resolveValue($context->find('footer_content'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6bb1703479fff422252ff00e65badd69(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="blockannotation">{{{annotation_content}}}</div>
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
                
                $buffer .= $indent . '    <div class="blockannotation">';
                $value = $this->resolveValue($context->find('annotation_content'), $context);
                $buffer .= $value;
                $buffer .= '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section2b9e9203587f2e846655e5eb10500e85(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<span id="sb-{{id}}" class="skip-block-to"></span>';
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
                
                $buffer .= $indent . '<span id="sb-';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="skip-block-to"></span>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
