<?php

class __Mustache_201957aad2991b3ebdbbd3899e73a39f extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<section class="tw-selectRegionPanel';
        // 'hide_on_mobile' section
        $value = $context->find('hide_on_mobile');
        $buffer .= $this->sectionE0b4cc76dcf01785a2a268ecc74207ee($context, $indent, $value);
        $buffer .= '"
';
        $buffer .= $indent . '    data-core-autoinitialise="true"
';
        $buffer .= $indent . '    data-core-autoinitialise-amd="totara_core/select_region_panel">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <div class="tw-selectRegionPanel__heading">
';
        $buffer .= $indent . '
';
        // 'title' section
        $value = $context->find('title');
        $buffer .= $this->sectionFc60e597c1ca25a39f3f6a72504621b7($context, $indent, $value);
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <div class="tw-selectRegionPanel__content">
';
        // 'selectors' section
        $value = $context->find('selectors');
        $buffer .= $this->section9e50fb0e0da1d5572a44b0095a8aa844($context, $indent, $value);
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</section>';

        return $buffer;
    }

    private function sectionE0b4cc76dcf01785a2a268ecc74207ee(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' tw-selectRegionPanel__hiddenOnSmall';
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
                
                $buffer .= ' tw-selectRegionPanel__hiddenOnSmall';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6cc92bd67b03e6a148ae3261976d461f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <span class="tw-selectRegionPanel__content_hidden">
                (<span class="tw-selectRegionPanel__heading_count" data-tw-selectRegionPanel-count=""></span>)
            </span>
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
                
                $buffer .= $indent . '            <span class="tw-selectRegionPanel__content_hidden">
';
                $buffer .= $indent . '                (<span class="tw-selectRegionPanel__heading_count" data-tw-selectRegionPanel-count=""></span>)
';
                $buffer .= $indent . '            </span>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5d48757675d92b7f9ff432b31aab68ae(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'clearall, totara_core';
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
                
                $buffer .= 'clearall, totara_core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA163b55814bf012d7ff95ccef747c708(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="tw-selectRegionPanel__heading_clear">
            <a href="#" class="tw-selectRegionPanel__heading_clear_link" data-tw-selectRegionPanel-clear="">
                {{#str}}clearall, totara_core{{/str}}
            </a>
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
                
                $buffer .= $indent . '        <div class="tw-selectRegionPanel__heading_clear">
';
                $buffer .= $indent . '            <a href="#" class="tw-selectRegionPanel__heading_clear_link" data-tw-selectRegionPanel-clear="">
';
                $buffer .= $indent . '                ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section5d48757675d92b7f9ff432b31aab68ae($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '            </a>
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFc60e597c1ca25a39f3f6a72504621b7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <h2 class="tw-selectRegionPanel__heading_text">
            {{{title}}}
            {{#display_active_count}}
            <span class="tw-selectRegionPanel__content_hidden">
                (<span class="tw-selectRegionPanel__heading_count" data-tw-selectRegionPanel-count=""></span>)
            </span>
            {{/display_active_count}}
        </h2>

        {{! Display clear btn }}
        {{#display_clear_trigger}}
        <div class="tw-selectRegionPanel__heading_clear">
            <a href="#" class="tw-selectRegionPanel__heading_clear_link" data-tw-selectRegionPanel-clear="">
                {{#str}}clearall, totara_core{{/str}}
            </a>
        </div>
        {{/display_clear_trigger}}

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
                
                $buffer .= $indent . '        <h2 class="tw-selectRegionPanel__heading_text">
';
                $buffer .= $indent . '            ';
                $value = $this->resolveValue($context->find('title'), $context);
                $buffer .= $value;
                $buffer .= '
';
                // 'display_active_count' section
                $value = $context->find('display_active_count');
                $buffer .= $this->section6cc92bd67b03e6a148ae3261976d461f($context, $indent, $value);
                $buffer .= $indent . '        </h2>
';
                $buffer .= $indent . '
';
                // 'display_clear_trigger' section
                $value = $context->find('display_clear_trigger');
                $buffer .= $this->sectionA163b55814bf012d7ff95ccef747c708($context, $indent, $value);
                $buffer .= $indent . '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE6f8c9d15e5a1d4136e9dad20046614f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'tw-selectRegionPanel__selector_small';
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
                
                $buffer .= 'tw-selectRegionPanel__selector_small';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section18e8e09ddc91a0a8b02c671fba2f58b6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#placeholder_show}}tw-selectRegionPanel__selector_small{{/placeholder_show}}';
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
                
                // 'placeholder_show' section
                $value = $context->find('placeholder_show');
                $buffer .= $this->sectionE6f8c9d15e5a1d4136e9dad20046614f($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section68eba92461b4a554c5f65c6bc6d24114(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{^placeholder_show}}
            <h3 id="{{{key}}}" class="tw-selectRegionPanel__selector_header">
                {{{title}}}
            </h3>
            {{/placeholder_show}}

            {{> &&template_name }}
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
                
                // 'placeholder_show' inverted section
                $value = $context->find('placeholder_show');
                if (empty($value)) {
                    
                    $buffer .= $indent . '            <h3 id="';
                    $value = $this->resolveValue($context->find('key'), $context);
                    $buffer .= $value;
                    $buffer .= '" class="tw-selectRegionPanel__selector_header">
';
                    $buffer .= $indent . '                ';
                    $value = $this->resolveValue($context->find('title'), $context);
                    $buffer .= $value;
                    $buffer .= '
';
                    $buffer .= $indent . '            </h3>
';
                }
                $buffer .= $indent . '
';
                // Totara hack: if the partial starts with "." then try
                // to resolve it from the current context.
                $partialstr = '&&template_name';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context, $indent . '            ');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9e50fb0e0da1d5572a44b0095a8aa844(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <section class="tw-selectRegionPanel__selector
        {{#template_data}}{{#placeholder_show}}tw-selectRegionPanel__selector_small{{/placeholder_show}}{{/template_data}}">
            {{#template_data}}
            {{^placeholder_show}}
            <h3 id="{{{key}}}" class="tw-selectRegionPanel__selector_header">
                {{{title}}}
            </h3>
            {{/placeholder_show}}

            {{> &&template_name }}
            {{/template_data}}
        </section>
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
                
                $buffer .= $indent . '        <section class="tw-selectRegionPanel__selector
';
                $buffer .= $indent . '        ';
                // 'template_data' section
                $value = $context->find('template_data');
                $buffer .= $this->section18e8e09ddc91a0a8b02c671fba2f58b6($context, $indent, $value);
                $buffer .= '">
';
                // 'template_data' section
                $value = $context->find('template_data');
                $buffer .= $this->section68eba92461b4a554c5f65c6bc6d24114($context, $indent, $value);
                $buffer .= $indent . '        </section>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
