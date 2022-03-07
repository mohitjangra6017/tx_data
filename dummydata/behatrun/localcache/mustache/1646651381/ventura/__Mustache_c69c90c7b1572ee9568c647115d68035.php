<?php

class __Mustache_c69c90c7b1572ee9568c647115d68035 extends Mustache_Template
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
        $buffer .= $indent . '<div class="tw-profileCard">
';
        // 'profile_picture_url' section
        $value = $context->find('profile_picture_url');
        $buffer .= $this->section4c0d399d4e2b892bacddb8e89814afca($context, $indent, $value);
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <div class="tw-profileCard__description">
';
        // 'fields' section
        $value = $context->find('fields');
        $buffer .= $this->section35d3cb9e321fde1940e18c5bee29225e($context, $indent, $value);
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</div>';

        return $buffer;
    }

    private function section4c0d399d4e2b892bacddb8e89814afca(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <a
            href="{{profile_url}}"
            class="tw-profileCard__avatar"
        >
            <img
                alt="{{profile_picture_alt}}"
                src="{{profile_picture_url}}"
                class="tw-profileCard__avatar__img"
            />
        </a>
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
                
                $buffer .= $indent . '        <a
';
                $buffer .= $indent . '            href="';
                $value = $this->resolveValue($context->find('profile_url'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '            class="tw-profileCard__avatar"
';
                $buffer .= $indent . '        >
';
                $buffer .= $indent . '            <img
';
                $buffer .= $indent . '                alt="';
                $value = $this->resolveValue($context->find('profile_picture_alt'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                src="';
                $value = $this->resolveValue($context->find('profile_picture_url'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                class="tw-profileCard__avatar__img"
';
                $buffer .= $indent . '            />
';
                $buffer .= $indent . '        </a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section24221e852f3563a2ac36f4a4526dd6fe(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        <h4 class="tw-profileCard__description__link__header">
                            {{value}}
                        </h4>
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
                
                $buffer .= $indent . '                        <h4 class="tw-profileCard__description__link__header">
';
                $buffer .= $indent . '                            ';
                $value = $this->resolveValue($context->find('value'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '
';
                $buffer .= $indent . '                        </h4>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD2a615cac577e4471146c4f86e1e1aac(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <a
                    href="{{associate_url}}"
                    class="tw-profileCard__description__link"
                >
                    {{#is_title}}
                        <h4 class="tw-profileCard__description__link__header">
                            {{value}}
                        </h4>
                    {{/is_title}}
                    {{^is_title}}
                        <span>{{value}}</span>
                    {{/is_title}}
                </a>
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
                
                $buffer .= $indent . '                <a
';
                $buffer .= $indent . '                    href="';
                $value = $this->resolveValue($context->find('associate_url'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                    class="tw-profileCard__description__link"
';
                $buffer .= $indent . '                >
';
                // 'is_title' section
                $value = $context->find('is_title');
                $buffer .= $this->section24221e852f3563a2ac36f4a4526dd6fe($context, $indent, $value);
                // 'is_title' inverted section
                $value = $context->find('is_title');
                if (empty($value)) {
                    
                    $buffer .= $indent . '                        <span>';
                    $value = $this->resolveValue($context->find('value'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '</span>
';
                }
                $buffer .= $indent . '                </a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7a79d893d26f87ecd88a155a32a8e254(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <h4 class="tw-profileCard__description__header">
                        {{value}}
                    </h4>
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
                
                $buffer .= $indent . '                    <h4 class="tw-profileCard__description__header">
';
                $buffer .= $indent . '                        ';
                $value = $this->resolveValue($context->find('value'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '
';
                $buffer .= $indent . '                    </h4>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section35d3cb9e321fde1940e18c5bee29225e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{#associate_url}}
                <a
                    href="{{associate_url}}"
                    class="tw-profileCard__description__link"
                >
                    {{#is_title}}
                        <h4 class="tw-profileCard__description__link__header">
                            {{value}}
                        </h4>
                    {{/is_title}}
                    {{^is_title}}
                        <span>{{value}}</span>
                    {{/is_title}}
                </a>
            {{/associate_url}}
            {{^associate_url}}
                {{#is_title}}
                    <h4 class="tw-profileCard__description__header">
                        {{value}}
                    </h4>
                {{/is_title}}
                {{^is_title}}
                    <p class="tw-profileCard__description__text tw-profileCard__description__typography--{{key}}">
                        {{ value }}
                    </p>
                {{/is_title}}
            {{/associate_url}}
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
                
                // 'associate_url' section
                $value = $context->find('associate_url');
                $buffer .= $this->sectionD2a615cac577e4471146c4f86e1e1aac($context, $indent, $value);
                // 'associate_url' inverted section
                $value = $context->find('associate_url');
                if (empty($value)) {
                    
                    // 'is_title' section
                    $value = $context->find('is_title');
                    $buffer .= $this->section7a79d893d26f87ecd88a155a32a8e254($context, $indent, $value);
                    // 'is_title' inverted section
                    $value = $context->find('is_title');
                    if (empty($value)) {
                        
                        $buffer .= $indent . '                    <p class="tw-profileCard__description__text tw-profileCard__description__typography--';
                        $value = $this->resolveValue($context->find('key'), $context);
                        $buffer .= call_user_func($this->mustache->getEscape(), $value);
                        $buffer .= '">
';
                        $buffer .= $indent . '                        ';
                        $value = $this->resolveValue($context->find('value'), $context);
                        $buffer .= call_user_func($this->mustache->getEscape(), $value);
                        $buffer .= '
';
                        $buffer .= $indent . '                    </p>
';
                    }
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
