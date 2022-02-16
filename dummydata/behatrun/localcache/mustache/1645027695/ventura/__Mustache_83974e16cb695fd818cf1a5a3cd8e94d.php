<?php

class __Mustache_83974e16cb695fd818cf1a5a3cd8e94d extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div
';
        $buffer .= $indent . '  class="tw-containerCourse-enrolmentBanner alert alert-info alert-with-icon"
';
        $buffer .= $indent . '  data-core-autoinitialise="true"
';
        $buffer .= $indent . '  data-core-autoinitialise-amd="container_course/enrolment_banner"
';
        $buffer .= $indent . '  role="log"
';
        $buffer .= $indent . '>
';
        $buffer .= $indent . '  <div class="alert-icon">
';
        $buffer .= $indent . '    ';
        // 'flex_icon' section
        $value = $context->find('flex_icon');
        $buffer .= $this->section091b273efe03c4bbb7a19896355e92b6($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <div class="tw-containerCourse-enrolmentBanner__messageBox alert-message">
';
        $buffer .= $indent . '    <span>';
        $value = $this->resolveValue($context->find('message'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '</span>
';
        // 'enrol_button' section
        $value = $context->find('enrol_button');
        $buffer .= $this->section6792578b531e60c45474313228dc3c83($context, $indent, $value);
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '</div>';

        return $buffer;
    }

    private function section091b273efe03c4bbb7a19896355e92b6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'core|notification-info, , , ft-size-200';
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
                
                $buffer .= 'core|notification-info, , , ft-size-200';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section789ea76063ab77aec9b5f46921152d51(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'enrol, core_enrol';
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
                
                $buffer .= 'enrol, core_enrol';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4dac05ab8631443a74209e73572f4cd0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <a
          href="{{url}}"
          class="tw-containerCourse-enrolmentBanner__enrolButton"
          data-course-id="{{course_id}}"
        >
          {{#str}}enrol, core_enrol{{/str}}
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
                $buffer .= $indent . '          href="';
                $value = $this->resolveValue($context->find('url'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '          class="tw-containerCourse-enrolmentBanner__enrolButton"
';
                $buffer .= $indent . '          data-course-id="';
                $value = $this->resolveValue($context->find('course_id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '        >
';
                $buffer .= $indent . '          ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section789ea76063ab77aec9b5f46921152d51($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '        </a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6792578b531e60c45474313228dc3c83(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
      {{#display}}
        <a
          href="{{url}}"
          class="tw-containerCourse-enrolmentBanner__enrolButton"
          data-course-id="{{course_id}}"
        >
          {{#str}}enrol, core_enrol{{/str}}
        </a>
      {{/display}}
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
                
                // 'display' section
                $value = $context->find('display');
                $buffer .= $this->section4dac05ab8631443a74209e73572f4cd0($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
