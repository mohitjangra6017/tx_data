<?php

class __Mustache_a6a73444aeb8276d715c9dcc09b411a8 extends Mustache_Template
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
        $buffer .= $indent . '<ul class="navExpand--list navExpand--listSecond" data-tw-navExpand-list="true">
';
        // 'children' section
        $value = $context->find('children');
        $buffer .= $this->sectionC17ceb229f27cfdbc18c6da308489016($context, $indent, $value);
        $buffer .= $indent . '</ul>
';

        return $buffer;
    }

    private function sectionE47af5d5d1ba948185a9d6b228fef4d4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' navExpand--list_item_hasChildren';
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
                
                $buffer .= ' navExpand--list_item_hasChildren';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1aa096555c814cdac0bbadfef2e2d1f9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' navExpand--list_item_selected';
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
                
                $buffer .= ' navExpand--list_item_selected';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD3ba4bfbe009b36dd41fb39266786c11(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' data-tw-totaraNav-hasChildren="true"';
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
                
                $buffer .= ' data-tw-totaraNav-hasChildren="true"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0e55b27ad044ba057d09ff050183ebe0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' aria-haspopup="true" aria-expanded="false"';
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
                
                $buffer .= ' aria-haspopup="true" aria-expanded="false"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section17017762c1b84c0268d3ff77389f90f9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'data-tw-totaraNav-chevron="true"';
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
                
                $buffer .= 'data-tw-totaraNav-chevron="true"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionAaa5082aa5faf806b528ea9553e5b2bd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        {{{external_link_icon}}}
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
                
                $buffer .= '
';
                $buffer .= $indent . '                        ';
                $value = $this->resolveValue($context->find('external_link_icon'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '                    ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section88f3a0425f940581199a6cb7829720ec(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' selected';
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
                
                $buffer .= ' selected';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionAfb14f0f5bda1fb985a92a591d742f0e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' navExpand--list_item_last';
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
                
                $buffer .= ' navExpand--list_item_last';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3716e0c319e41e6b1e5f1a8172971381(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                                    {{{external_link_icon}}}
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
                
                $buffer .= $indent . '                                    ';
                $value = $this->resolveValue($context->find('external_link_icon'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8016081b57cac37aeac73aed641ebee3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <li class="navExpand--list_item
                        {{#class_isselected}} selected{{/class_isselected}}
                        {{#class_islast}} navExpand--list_item_last{{/class_islast}}"
                         data-tw-totaraNav-item="true">
                        <a class="navExpand--list_item_link" target="{{target}}" href="{{url}}"
                            data-tw-navExpand-listLink="true">
                            <div class="totaraNav--expand_indent"></div>
                            <div class="navExpand--list_item_label">
                                {{linktext}}
                                {{#external_link}}
                                    {{{external_link_icon}}}
                                {{/external_link}}
                            </div>
                        </a>
                    </li>
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
                
                $buffer .= $indent . '                    <li class="navExpand--list_item
';
                $buffer .= $indent . '                        ';
                // 'class_isselected' section
                $value = $context->find('class_isselected');
                $buffer .= $this->section88f3a0425f940581199a6cb7829720ec($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                        ';
                // 'class_islast' section
                $value = $context->find('class_islast');
                $buffer .= $this->sectionAfb14f0f5bda1fb985a92a591d742f0e($context, $indent, $value);
                $buffer .= '"
';
                $buffer .= $indent . '                         data-tw-totaraNav-item="true">
';
                $buffer .= $indent . '                        <a class="navExpand--list_item_link" target="';
                $value = $this->resolveValue($context->find('target'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" href="';
                $value = $this->resolveValue($context->find('url'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                            data-tw-navExpand-listLink="true">
';
                $buffer .= $indent . '                            <div class="totaraNav--expand_indent"></div>
';
                $buffer .= $indent . '                            <div class="navExpand--list_item_label">
';
                $buffer .= $indent . '                                ';
                $value = $this->resolveValue($context->find('linktext'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '
';
                // 'external_link' section
                $value = $context->find('external_link');
                $buffer .= $this->section3716e0c319e41e6b1e5f1a8172971381($context, $indent, $value);
                $buffer .= $indent . '                            </div>
';
                $buffer .= $indent . '                        </a>
';
                $buffer .= $indent . '                    </li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7cc3fdd08e23eb0c9475123e21ed9812(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <ul class="navExpand--list navExpand--listThird" data-tw-navExpand-list="true">
                {{#children}}
                    <li class="navExpand--list_item
                        {{#class_isselected}} selected{{/class_isselected}}
                        {{#class_islast}} navExpand--list_item_last{{/class_islast}}"
                         data-tw-totaraNav-item="true">
                        <a class="navExpand--list_item_link" target="{{target}}" href="{{url}}"
                            data-tw-navExpand-listLink="true">
                            <div class="totaraNav--expand_indent"></div>
                            <div class="navExpand--list_item_label">
                                {{linktext}}
                                {{#external_link}}
                                    {{{external_link_icon}}}
                                {{/external_link}}
                            </div>
                        </a>
                    </li>
                {{/children}}
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
                
                $buffer .= $indent . '            <ul class="navExpand--list navExpand--listThird" data-tw-navExpand-list="true">
';
                // 'children' section
                $value = $context->find('children');
                $buffer .= $this->section8016081b57cac37aeac73aed641ebee3($context, $indent, $value);
                $buffer .= $indent . '            </ul>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC17ceb229f27cfdbc18c6da308489016(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <li id="{{class_name}}"
            class="navExpand--list_item
            {{#haschildren}} navExpand--list_item_hasChildren{{/haschildren}}
            {{#class_isselected}} navExpand--list_item_selected{{/class_isselected}}"
            {{#haschildren}} data-tw-totaraNav-hasChildren="true"{{/haschildren}}
             data-tw-totaraNav-item="true">
            <a class="navExpand--list_item_link" target="{{target}}" href="{{url}}" data-tw-navExpand-listLink="true"
                {{#haschildren}} aria-haspopup="true" aria-expanded="false"{{/haschildren}}>
                <div class="totaraNav--expand_indent" {{#haschildren}}data-tw-totaraNav-chevron="true"{{/haschildren}}></div>
                <div class="navExpand--list_item_label">
                    {{{linktext}}}
                    {{^haschildren}}{{#external_link}}
                        {{{external_link_icon}}}
                    {{/external_link}}{{/haschildren}}
                </div>
            </a>

            {{! Third level navigation, used for mobile }}
            {{#haschildren}}
            <ul class="navExpand--list navExpand--listThird" data-tw-navExpand-list="true">
                {{#children}}
                    <li class="navExpand--list_item
                        {{#class_isselected}} selected{{/class_isselected}}
                        {{#class_islast}} navExpand--list_item_last{{/class_islast}}"
                         data-tw-totaraNav-item="true">
                        <a class="navExpand--list_item_link" target="{{target}}" href="{{url}}"
                            data-tw-navExpand-listLink="true">
                            <div class="totaraNav--expand_indent"></div>
                            <div class="navExpand--list_item_label">
                                {{linktext}}
                                {{#external_link}}
                                    {{{external_link_icon}}}
                                {{/external_link}}
                            </div>
                        </a>
                    </li>
                {{/children}}
            </ul>
            {{/haschildren}}

        </li>
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
                
                $buffer .= $indent . '        <li id="';
                $value = $this->resolveValue($context->find('class_name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '            class="navExpand--list_item
';
                $buffer .= $indent . '            ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->sectionE47af5d5d1ba948185a9d6b228fef4d4($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '            ';
                // 'class_isselected' section
                $value = $context->find('class_isselected');
                $buffer .= $this->section1aa096555c814cdac0bbadfef2e2d1f9($context, $indent, $value);
                $buffer .= '"
';
                $buffer .= $indent . '            ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->sectionD3ba4bfbe009b36dd41fb39266786c11($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '             data-tw-totaraNav-item="true">
';
                $buffer .= $indent . '            <a class="navExpand--list_item_link" target="';
                $value = $this->resolveValue($context->find('target'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" href="';
                $value = $this->resolveValue($context->find('url'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" data-tw-navExpand-listLink="true"
';
                $buffer .= $indent . '                ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->section0e55b27ad044ba057d09ff050183ebe0($context, $indent, $value);
                $buffer .= '>
';
                $buffer .= $indent . '                <div class="totaraNav--expand_indent" ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->section17017762c1b84c0268d3ff77389f90f9($context, $indent, $value);
                $buffer .= '></div>
';
                $buffer .= $indent . '                <div class="navExpand--list_item_label">
';
                $buffer .= $indent . '                    ';
                $value = $this->resolveValue($context->find('linktext'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '                    ';
                // 'haschildren' inverted section
                $value = $context->find('haschildren');
                if (empty($value)) {
                    
                    // 'external_link' section
                    $value = $context->find('external_link');
                    $buffer .= $this->sectionAaa5082aa5faf806b528ea9553e5b2bd($context, $indent, $value);
                }
                $buffer .= '
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '            </a>
';
                $buffer .= $indent . '
';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->section7cc3fdd08e23eb0c9475123e21ed9812($context, $indent, $value);
                $buffer .= $indent . '
';
                $buffer .= $indent . '        </li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
