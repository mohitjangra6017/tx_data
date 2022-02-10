<?php

class __Mustache_90d89408745f0a1756dfa3b9f45978ee extends Mustache_Template
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
        $buffer .= $indent . '<div class="mod_facetoface__action';
        // 'class' section
        $value = $context->find('class');
        $buffer .= $this->section5c7c2c42227c014d9f647f37a06ae343($context, $indent, $value);
        // 'align' section
        $value = $context->find('align');
        $buffer .= $this->sectionE786e1546e1b0e9ab11e226b0e4637ba($context, $indent, $value);
        // 'group' section
        $value = $context->find('group');
        $buffer .= $this->sectionB7dcf711c5134470f9db9dd7eec86378($context, $indent, $value);
        $buffer .= '"';
        // 'id' section
        $value = $context->find('id');
        $buffer .= $this->section22ab3923ba2cfc3cff171c9ace86b5d6($context, $indent, $value);
        $buffer .= ' role="group"';
        // 'label' section
        $value = $context->find('label');
        $buffer .= $this->section6ebbe96d947f52a25c4437e036f5f828($context, $indent, $value);
        $buffer .= '>
';
        // 'commandlinks' section
        $value = $context->find('commandlinks');
        $buffer .= $this->section4fc919c712d3a488833471a5a0f7064e($context, $indent, $value);
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function section5c7c2c42227c014d9f647f37a06ae343(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' mod_facetoface__action-{{.}}';
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
                
                $buffer .= ' mod_facetoface__action-';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE786e1546e1b0e9ab11e226b0e4637ba(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' mod_facetoface__action--{{.}}';
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
                
                $buffer .= ' mod_facetoface__action--';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB7dcf711c5134470f9db9dd7eec86378(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' btn-group';
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
                
                $buffer .= ' btn-group';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section22ab3923ba2cfc3cff171c9ace86b5d6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' id="{{.}}"';
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
                
                $buffer .= ' id="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6ebbe96d947f52a25c4437e036f5f828(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' aria-label="{{.}}"';
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
                
                $buffer .= ' aria-label="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
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

    private function sectionEf27803ff5ee1ff2bf718b76c38200fa(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<span role="link" aria-disabled="true" class="disabled commandlink mod_facetoface__action__{{name}}">{{#context}}{{> &&template }}{{/context}}</span>';
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
                
                $buffer .= '<span role="link" aria-disabled="true" class="disabled commandlink mod_facetoface__action__';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                // 'context' section
                $value = $context->find('context');
                $buffer .= $this->section36d0242880fd41b624218ba4c8470eb3($context, $indent, $value);
                $buffer .= '</span>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section81706fad4bdf1c54d67a99457b867e35(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'btn-primary';
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
                
                $buffer .= 'btn-primary';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section79cccc83d2a6c3605503a1be6ea3fa3e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{#icon}}<span role="link" aria-disabled="true" class="disabled commandlink mod_facetoface__action__{{name}}">{{#context}}{{> &&template }}{{/context}}</span>{{/icon}}
            {{^icon}}<span role="button" aria-disabled="true" class="disabled commandlink btn {{#primary}}btn-primary{{/primary}}{{^primary}}btn-default{{/primary}} mod_facetoface__action__{{name}}">{{{text}}}</span>{{/icon}}
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
                // 'icon' section
                $value = $context->find('icon');
                $buffer .= $this->sectionEf27803ff5ee1ff2bf718b76c38200fa($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '            ';
                // 'icon' inverted section
                $value = $context->find('icon');
                if (empty($value)) {
                    
                    $buffer .= '<span role="button" aria-disabled="true" class="disabled commandlink btn ';
                    // 'primary' section
                    $value = $context->find('primary');
                    $buffer .= $this->section81706fad4bdf1c54d67a99457b867e35($context, $indent, $value);
                    // 'primary' inverted section
                    $value = $context->find('primary');
                    if (empty($value)) {
                        
                        $buffer .= 'btn-default';
                    }
                    $buffer .= ' mod_facetoface__action__';
                    $value = $this->resolveValue($context->find('name'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '">';
                    $value = $this->resolveValue($context->find('text'), $context);
                    $buffer .= $value;
                    $buffer .= '</span>';
                }
                $buffer .= '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section969f1afa3dc62c1bfeb94348d3cc7086(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<a href="{{href}}" class="commandlink mod_facetoface__action__{{name}}">{{#context}}{{> &&template }}{{/context}}</a>';
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
                
                $buffer .= '<a href="';
                $value = $this->resolveValue($context->find('href'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="commandlink mod_facetoface__action__';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                // 'context' section
                $value = $context->find('context');
                $buffer .= $this->section36d0242880fd41b624218ba4c8470eb3($context, $indent, $value);
                $buffer .= '</a>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4fc919c712d3a488833471a5a0f7064e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        {{#disabled}}
            {{#icon}}<span role="link" aria-disabled="true" class="disabled commandlink mod_facetoface__action__{{name}}">{{#context}}{{> &&template }}{{/context}}</span>{{/icon}}
            {{^icon}}<span role="button" aria-disabled="true" class="disabled commandlink btn {{#primary}}btn-primary{{/primary}}{{^primary}}btn-default{{/primary}} mod_facetoface__action__{{name}}">{{{text}}}</span>{{/icon}}
        {{/disabled}}
        {{^disabled}}
            {{#icon}}<a href="{{href}}" class="commandlink mod_facetoface__action__{{name}}">{{#context}}{{> &&template }}{{/context}}</a>{{/icon}}
            {{^icon}}<a href="{{href}}" role="button" class="commandlink btn {{#primary}}btn-primary{{/primary}}{{^primary}}btn-default{{/primary}} mod_facetoface__action__{{name}}">{{{text}}}</a>{{/icon}}
        {{/disabled}}
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
                
                // 'disabled' section
                $value = $context->find('disabled');
                $buffer .= $this->section79cccc83d2a6c3605503a1be6ea3fa3e($context, $indent, $value);
                // 'disabled' inverted section
                $value = $context->find('disabled');
                if (empty($value)) {
                    
                    $buffer .= $indent . '            ';
                    // 'icon' section
                    $value = $context->find('icon');
                    $buffer .= $this->section969f1afa3dc62c1bfeb94348d3cc7086($context, $indent, $value);
                    $buffer .= '
';
                    $buffer .= $indent . '            ';
                    // 'icon' inverted section
                    $value = $context->find('icon');
                    if (empty($value)) {
                        
                        $buffer .= '<a href="';
                        $value = $this->resolveValue($context->find('href'), $context);
                        $buffer .= call_user_func($this->mustache->getEscape(), $value);
                        $buffer .= '" role="button" class="commandlink btn ';
                        // 'primary' section
                        $value = $context->find('primary');
                        $buffer .= $this->section81706fad4bdf1c54d67a99457b867e35($context, $indent, $value);
                        // 'primary' inverted section
                        $value = $context->find('primary');
                        if (empty($value)) {
                            
                            $buffer .= 'btn-default';
                        }
                        $buffer .= ' mod_facetoface__action__';
                        $value = $this->resolveValue($context->find('name'), $context);
                        $buffer .= call_user_func($this->mustache->getEscape(), $value);
                        $buffer .= '">';
                        $value = $this->resolveValue($context->find('text'), $context);
                        $buffer .= $value;
                        $buffer .= '</a>';
                    }
                    $buffer .= '
';
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
