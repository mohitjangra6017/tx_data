<?php

class __Mustache_85cf26816327031755d8d0d18ce11740 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<section class="tw-grid"
';
        $buffer .= $indent . '    role="list"
';
        $buffer .= $indent . '    data-tw-grid=""
';
        $buffer .= $indent . '    data-core-enableactiveitem="';
        $value = $this->resolveValue($context->find('enableactiveitem'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"
';
        $buffer .= $indent . '    data-core-autoinitialise="true"
';
        $buffer .= $indent . '    data-core-autoinitialise-amd="totara_core/grid">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    ';
        // 'tiles' section
        $value = $context->find('tiles');
        $buffer .= $this->section81bc863a3dd3a5cd077cacf5d38c7a01($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '</section>
';

        return $buffer;
    }

    private function sectionEb523747515da9b5388140f1f78c401d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'tw-grid__item--single-column';
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
                
                $buffer .= 'tw-grid__item--single-column';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB37d79ae038e3313c97ce7f6026f3219(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{itemid}}';
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
                
                $value = $this->resolveValue($context->find('itemid'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE55ad34ea59c629a5cfeb33f495ad287(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> &&template_name }}';
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
                $partialstr = '&&template_name';
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

    private function section81bc863a3dd3a5cd077cacf5d38c7a01(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{!
  }}<div class="tw-grid__item
        {{#single_column}}tw-grid__item--single-column{{/single_column}}
        {{^single_column}}tw-grid__item--multi-column{{/single_column}}"
        role="listitem"
        data-tw-grid-item=""
        data-tw-grid-item-ID="{{#template_data}}{{itemid}}{{/template_data}}">
        {{#template_data}}{{> &&template_name }}{{/template_data}}{{!
  }}</div>';
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
                
                $buffer .= '<div class="tw-grid__item
';
                $buffer .= $indent . '        ';
                // 'single_column' section
                $value = $context->find('single_column');
                $buffer .= $this->sectionEb523747515da9b5388140f1f78c401d($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '        ';
                // 'single_column' inverted section
                $value = $context->find('single_column');
                if (empty($value)) {
                    
                    $buffer .= 'tw-grid__item--multi-column';
                }
                $buffer .= '"
';
                $buffer .= $indent . '        role="listitem"
';
                $buffer .= $indent . '        data-tw-grid-item=""
';
                $buffer .= $indent . '        data-tw-grid-item-ID="';
                // 'template_data' section
                $value = $context->find('template_data');
                $buffer .= $this->sectionB37d79ae038e3313c97ce7f6026f3219($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '        ';
                // 'template_data' section
                $value = $context->find('template_data');
                $buffer .= $this->sectionE55ad34ea59c629a5cfeb33f495ad287($context, $indent, $value);
                $buffer .= '</div>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
