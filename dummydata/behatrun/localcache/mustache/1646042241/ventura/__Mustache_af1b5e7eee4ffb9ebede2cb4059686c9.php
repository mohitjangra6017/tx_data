<?php

class __Mustache_af1b5e7eee4ffb9ebede2cb4059686c9 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'hero_image' section
        $value = $context->find('hero_image');
        $buffer .= $this->sectionD921aeb035915c407a27578c07569afb($context, $indent, $value);
        // 'hasinstructions' section
        $value = $context->find('hasinstructions');
        $buffer .= $this->sectionCd6d6986e903339ec605e355753e3f3a($context, $indent, $value);
        // 'hasinstructions' inverted section
        $value = $context->find('hasinstructions');
        if (empty($value)) {
            
            $buffer .= $indent . '<div class="loginbox clearfix onecolumn">
';
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <a class="skip-block" href="#login-skipped">';
        $value = $this->resolveValue($context->find('skiplinktext'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '</a>
';
        $buffer .= $indent . '    <div class="loginpanel">
';
        $buffer .= $indent . '        <h2>';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section40ab32d843ea367ab8a69a6ea2e65476($context, $indent, $value);
        $buffer .= '</h2>
';
        $buffer .= $indent . '        <hr />
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        <div class="subcontent loginsub">
';
        // 'error' section
        $value = $context->find('error');
        $buffer .= $this->section25567bc959476af764f2cb8f2642b839($context, $indent, $value);
        $buffer .= $indent . '            <form action="';
        $value = $this->resolveValue($context->find('loginurl'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" method="post" id="login" data-core-autoinitialise="true" data-core-autoinitialise-amd="core/form_duplicate_prevent">
';
        $buffer .= $indent . '                <div class="loginform">
';
        $buffer .= $indent . '                    <div class="form-label">
';
        $buffer .= $indent . '                        <label for="username">
';
        // 'canloginbyemail' inverted section
        $value = $context->find('canloginbyemail');
        if (empty($value)) {
            
            $buffer .= $indent . '                                ';
            // 'str' section
            $value = $context->find('str');
            $buffer .= $this->section27e9419edc620e0e1872d2a6521f1533($context, $indent, $value);
            $buffer .= '
';
        }
        // 'canloginbyemail' section
        $value = $context->find('canloginbyemail');
        $buffer .= $this->section37edb88dd48adb1f28a74b50cfa5a3cd($context, $indent, $value);
        $buffer .= $indent . '                        </label>
';
        $buffer .= $indent . '                    </div>
';
        $buffer .= $indent . '                    <div class="form-input">
';
        $buffer .= $indent . '                        <input
';
        $buffer .= $indent . '                            type="text"
';
        $buffer .= $indent . '                            name="username"
';
        $buffer .= $indent . '                            id="username"
';
        $buffer .= $indent . '                            size="15"
';
        $buffer .= $indent . '                            value="';
        $value = $this->resolveValue($context->find('username'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"
';
        $buffer .= $indent . '                            ';
        // 'error' section
        $value = $context->find('error');
        $buffer .= $this->sectionFa045eed3faab0d46a00928c84f0defb($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '                        >
';
        $buffer .= $indent . '                    </div>
';
        $buffer .= $indent . '                    <div class="clearer"><!-- --></div>
';
        $buffer .= $indent . '                    <div class="form-label">
';
        $buffer .= $indent . '                        <label for="password">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->sectionE056be559d6d01a9bd2bf6f760f8e3e3($context, $indent, $value);
        $buffer .= '</label>
';
        $buffer .= $indent . '                    </div>
';
        $buffer .= $indent . '                    <div class="form-input">
';
        $buffer .= $indent . '                        <input
';
        $buffer .= $indent . '                            type="password"
';
        $buffer .= $indent . '                            name="password"
';
        $buffer .= $indent . '                            id="password"
';
        $buffer .= $indent . '                            size="15"
';
        $buffer .= $indent . '                            value=""
';
        $buffer .= $indent . '                            ';
        // 'error' section
        $value = $context->find('error');
        $buffer .= $this->sectionFa045eed3faab0d46a00928c84f0defb($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '                        >
';
        $buffer .= $indent . '                    </div>
';
        $buffer .= $indent . '                    <input type="hidden" name="logintoken" value="';
        $value = $this->resolveValue($context->find('logintoken'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" />
';
        $buffer .= $indent . '                </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '                <div class="clearer"><!-- --></div>
';
        // 'rememberusername' section
        $value = $context->find('rememberusername');
        $buffer .= $this->section64892a175eada62847b84cb105ea2b65($context, $indent, $value);
        $buffer .= $indent . '                <div class="clearer"><!-- --></div>
';
        $buffer .= $indent . '                <input id="anchor" type="hidden" name="anchor" value="" />
';
        $buffer .= $indent . '                <script>document.getElementById(\'anchor\').value = location.hash;</script>
';
        $buffer .= $indent . '                <input type="submit" id="loginbtn" value=';
        // 'quote' section
        $value = $context->find('quote');
        $buffer .= $this->section5860067bda3357cbf2ce340f4d8846ac($context, $indent, $value);
        $buffer .= ' />
';
        $buffer .= $indent . '            </form>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '            <div class="forgetpass">
';
        $buffer .= $indent . '                <a href="';
        $value = $this->resolveValue($context->find('forgotpasswordurl'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section75744af61ed4d4a7233f5e316671ca4f($context, $indent, $value);
        $buffer .= '</a>
';
        $buffer .= $indent . '            </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '
';
        // 'canloginasguest' section
        $value = $context->find('canloginasguest');
        $buffer .= $this->sectionE3d0e6c0e0878ea46cf55248e98bb994($context, $indent, $value);
        $buffer .= $indent . '
';
        $buffer .= $indent . '        <div class="desc">
';
        $buffer .= $indent . '            ';
        $value = $this->resolveValue($context->find('cookieshelpiconformatted'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '            ';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section946f040476794b323defa7b00688109b($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    <span id="login-skipped" class="skip-block-to"></span>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <div class="signuppanel">
';
        // 'hasinstructions' section
        $value = $context->find('hasinstructions');
        $buffer .= $this->sectionC3d14eb9f5e239ba54821e9b0d1090f7($context, $indent, $value);
        $buffer .= $indent . '
';
        // 'hasidentityproviders' section
        $value = $context->find('hasidentityproviders');
        $buffer .= $this->sectionD28b6952bedd54024c316931478053ea($context, $indent, $value);
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->section480c63216ef76e91d09d372587f79b1b($context, $indent, $value);

        return $buffer;
    }

    private function sectionD921aeb035915c407a27578c07569afb(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div id="login_hero-image" >
        <img src="{{hero_image}}" alt="{{hero_alt}}"/>
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
                
                $buffer .= $indent . '    <div id="login_hero-image" >
';
                $buffer .= $indent . '        <img src="';
                $value = $this->resolveValue($context->find('hero_image'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" alt="';
                $value = $this->resolveValue($context->find('hero_alt'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"/>
';
                $buffer .= $indent . '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionCd6d6986e903339ec605e355753e3f3a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
<div class="loginbox clearfix twocolumns">
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
                
                $buffer .= $indent . '<div class="loginbox clearfix twocolumns">
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section40ab32d843ea367ab8a69a6ea2e65476(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' login ';
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
                
                $buffer .= ' login ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section25567bc959476af764f2cb8f2642b839(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <div id="loginerrormessage" class="loginerrors" role="alert">
                    {{error}}
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
                
                $buffer .= $indent . '                <div id="loginerrormessage" class="loginerrors" role="alert">
';
                $buffer .= $indent . '                    ';
                $value = $this->resolveValue($context->find('error'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '
';
                $buffer .= $indent . '                </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section27e9419edc620e0e1872d2a6521f1533(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' username ';
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
                
                $buffer .= ' username ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section22141a6741c33f407ef6171795337eec(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' usernameemail ';
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
                
                $buffer .= ' usernameemail ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section37edb88dd48adb1f28a74b50cfa5a3cd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                                {{#str}} usernameemail {{/str}}
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
                
                $buffer .= $indent . '                                ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section22141a6741c33f407ef6171795337eec($context, $indent, $value);
                $buffer .= '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFa045eed3faab0d46a00928c84f0defb(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'aria-describedby="loginerrormessage"';
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
                
                $buffer .= 'aria-describedby="loginerrormessage"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE056be559d6d01a9bd2bf6f760f8e3e3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' password ';
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
                
                $buffer .= ' password ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE6c044fe8710d3502dd5cb9686c32f3f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'checked="checked"';
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
                
                $buffer .= 'checked="checked"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section64892a175eada62847b84cb105ea2b65(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <div class="rememberusername">
                        <input type="checkbox" name="rememberusernamechecked" id="rememberusernamechecked" value="1" {{#rememberusernamechecked}}checked="checked"{{/rememberusernamechecked}} />
                        <label for="rememberusernamechecked">{{rememberusernamelabel}}</label>
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
                
                $buffer .= $indent . '                    <div class="rememberusername">
';
                $buffer .= $indent . '                        <input type="checkbox" name="rememberusernamechecked" id="rememberusernamechecked" value="1" ';
                // 'rememberusernamechecked' section
                $value = $context->find('rememberusernamechecked');
                $buffer .= $this->sectionE6c044fe8710d3502dd5cb9686c32f3f($context, $indent, $value);
                $buffer .= ' />
';
                $buffer .= $indent . '                        <label for="rememberusernamechecked">';
                $value = $this->resolveValue($context->find('rememberusernamelabel'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</label>
';
                $buffer .= $indent . '                    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5860067bda3357cbf2ce340f4d8846ac(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#str}} login {{/str}}';
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
                
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section40ab32d843ea367ab8a69a6ea2e65476($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section75744af61ed4d4a7233f5e316671ca4f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' forgotten ';
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
                
                $buffer .= ' forgotten ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7ed3c7b6104aa334de470f79728beebf(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' someallowguest ';
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
                
                $buffer .= ' someallowguest ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4bb51e24d0fd02006f3e489e80a0b365(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' loginguest ';
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
                
                $buffer .= ' loginguest ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD7d848bbe73d55bc84081585c15d0549(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#str}} loginguest {{/str}}';
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
                
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section4bb51e24d0fd02006f3e489e80a0b365($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE3d0e6c0e0878ea46cf55248e98bb994(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="subcontent guestsub">
                <div class="desc">{{#str}} someallowguest {{/str}}</div>
                <form action="{{loginurl}}" method="post" id="guestlogin" data-core-autoinitialise="true" data-core-autoinitialise-amd="core/form_duplicate_prevent">
                    <div class="guestform">
                        <input type="hidden" name="username" value="guest" />
                        <input type="hidden" name="password" value="guest" />
                        <input type="hidden" name="logintoken" value="{{logintoken}}" />
                        <input type="submit" value={{#quote}}{{#str}} loginguest {{/str}}{{/quote}} />
                    </div>
                </form>
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
                
                $buffer .= $indent . '            <div class="subcontent guestsub">
';
                $buffer .= $indent . '                <div class="desc">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section7ed3c7b6104aa334de470f79728beebf($context, $indent, $value);
                $buffer .= '</div>
';
                $buffer .= $indent . '                <form action="';
                $value = $this->resolveValue($context->find('loginurl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" method="post" id="guestlogin" data-core-autoinitialise="true" data-core-autoinitialise-amd="core/form_duplicate_prevent">
';
                $buffer .= $indent . '                    <div class="guestform">
';
                $buffer .= $indent . '                        <input type="hidden" name="username" value="guest" />
';
                $buffer .= $indent . '                        <input type="hidden" name="password" value="guest" />
';
                $buffer .= $indent . '                        <input type="hidden" name="logintoken" value="';
                $value = $this->resolveValue($context->find('logintoken'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" />
';
                $buffer .= $indent . '                        <input type="submit" value=';
                // 'quote' section
                $value = $context->find('quote');
                $buffer .= $this->sectionD7d848bbe73d55bc84081585c15d0549($context, $indent, $value);
                $buffer .= ' />
';
                $buffer .= $indent . '                    </div>
';
                $buffer .= $indent . '                </form>
';
                $buffer .= $indent . '            </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section946f040476794b323defa7b00688109b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' cookiesenabled ';
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
                
                $buffer .= ' cookiesenabled ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9e7e1656a410e28ad447bc910c287930(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' firsttime ';
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
                
                $buffer .= ' firsttime ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6b89ece2906ec5b5e1981e97e4664025(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' startsignup ';
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
                
                $buffer .= ' startsignup ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section862e344df30a1185502b9ac2cd1ab6e6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#str}} startsignup {{/str}}';
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
                
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section6b89ece2906ec5b5e1981e97e4664025($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1ddb05416ddee46b3d8004a4e90ef7b9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <div class="signupform">
                    <form action="{{signupurl}}" method="get" id="signup" data-core-autoinitialise="true" data-core-autoinitialise-amd="core/form_duplicate_prevent">
                        <div>
                            <input type="submit" value={{#quote}}{{#str}} startsignup {{/str}}{{/quote}} />
                        </div>
                    </form>
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
                
                $buffer .= $indent . '                <div class="signupform">
';
                $buffer .= $indent . '                    <form action="';
                $value = $this->resolveValue($context->find('signupurl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" method="get" id="signup" data-core-autoinitialise="true" data-core-autoinitialise-amd="core/form_duplicate_prevent">
';
                $buffer .= $indent . '                        <div>
';
                $buffer .= $indent . '                            <input type="submit" value=';
                // 'quote' section
                $value = $context->find('quote');
                $buffer .= $this->section862e344df30a1185502b9ac2cd1ab6e6($context, $indent, $value);
                $buffer .= ' />
';
                $buffer .= $indent . '                        </div>
';
                $buffer .= $indent . '                    </form>
';
                $buffer .= $indent . '                </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC3d14eb9f5e239ba54821e9b0d1090f7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <h2>{{#str}} firsttime {{/str}}</h2>
        <hr />
        <div class="subcontent">
            {{{instructions}}}
            {{#cansignup}}
                <div class="signupform">
                    <form action="{{signupurl}}" method="get" id="signup" data-core-autoinitialise="true" data-core-autoinitialise-amd="core/form_duplicate_prevent">
                        <div>
                            <input type="submit" value={{#quote}}{{#str}} startsignup {{/str}}{{/quote}} />
                        </div>
                    </form>
                </div>
            {{/cansignup}}
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
                
                $buffer .= $indent . '        <h2>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section9e7e1656a410e28ad447bc910c287930($context, $indent, $value);
                $buffer .= '</h2>
';
                $buffer .= $indent . '        <hr />
';
                $buffer .= $indent . '        <div class="subcontent">
';
                $buffer .= $indent . '            ';
                $value = $this->resolveValue($context->find('instructions'), $context);
                $buffer .= $value;
                $buffer .= '
';
                // 'cansignup' section
                $value = $context->find('cansignup');
                $buffer .= $this->section1ddb05416ddee46b3d8004a4e90ef7b9($context, $indent, $value);
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE384f0e9b1fcc321a1a78dba1d43f63f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' potentialidps, auth ';
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
                
                $buffer .= ' potentialidps, auth ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section2cc4f001fac176da7811af0145b65676(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                            <a href="{{{url}}}" class="tw-oauth2-login-button-{{issuertype}}">
                                <img src="{{buttonimageurl}}" alt="{{name}}"/>
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
                
                $buffer .= $indent . '                            <a href="';
                $value = $this->resolveValue($context->find('url'), $context);
                $buffer .= $value;
                $buffer .= '" class="tw-oauth2-login-button-';
                $value = $this->resolveValue($context->find('issuertype'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $buffer .= $indent . '                                <img src="';
                $value = $this->resolveValue($context->find('buttonimageurl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" alt="';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"/>
';
                $buffer .= $indent . '                            </a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section75d4f52319a19f9c285ee4104cead05e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                                <img src="{{iconurl}}" alt="" width="24" height="24"/>
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
                
                $buffer .= $indent . '                                <img src="';
                $value = $this->resolveValue($context->find('iconurl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" alt="" width="24" height="24"/>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC0bc804547775f1c50e9df6284b52b95(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{>&&template}}';
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

    private function sectionBf5f06bf4f857c813eb4e2ae55d5edd5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                                {{#context}}{{>&&template}}{{/context}}
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
                
                $buffer .= $indent . '                                ';
                // 'context' section
                $value = $context->find('context');
                $buffer .= $this->sectionC0bc804547775f1c50e9df6284b52b95($context, $indent, $value);
                $buffer .= '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFa5a2de73ab5ddfb9db5962ea9d0e8f1(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <div class="potentialidp">
                        {{#buttonimageurl}}
                            <a href="{{{url}}}" class="tw-oauth2-login-button-{{issuertype}}">
                                <img src="{{buttonimageurl}}" alt="{{name}}"/>
                            </a>
                        {{/buttonimageurl}}
                        {{^buttonimageurl}}
                        <a href="{{{url}}}" class="btn btn-default">
                            {{#iconurl}}
                                <img src="{{iconurl}}" alt="" width="24" height="24"/>
                            {{/iconurl}}
                            {{#icon}}
                                {{#context}}{{>&&template}}{{/context}}
                            {{/icon}}
                            {{name}}
                        </a>
                        {{/buttonimageurl}}
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
                
                $buffer .= $indent . '                    <div class="potentialidp">
';
                // 'buttonimageurl' section
                $value = $context->find('buttonimageurl');
                $buffer .= $this->section2cc4f001fac176da7811af0145b65676($context, $indent, $value);
                // 'buttonimageurl' inverted section
                $value = $context->find('buttonimageurl');
                if (empty($value)) {
                    
                    $buffer .= $indent . '                        <a href="';
                    $value = $this->resolveValue($context->find('url'), $context);
                    $buffer .= $value;
                    $buffer .= '" class="btn btn-default">
';
                    // 'iconurl' section
                    $value = $context->find('iconurl');
                    $buffer .= $this->section75d4f52319a19f9c285ee4104cead05e($context, $indent, $value);
                    // 'icon' section
                    $value = $context->find('icon');
                    $buffer .= $this->sectionBf5f06bf4f857c813eb4e2ae55d5edd5($context, $indent, $value);
                    $buffer .= $indent . '                            ';
                    $value = $this->resolveValue($context->find('name'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '
';
                    $buffer .= $indent . '                        </a>
';
                }
                $buffer .= $indent . '                    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD28b6952bedd54024c316931478053ea(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="subcontent potentialidps">
            <h6>{{#str}} potentialidps, auth {{/str}}</h6>
            <div class="potentialidplist">
                {{#identityproviders}}
                    <div class="potentialidp">
                        {{#buttonimageurl}}
                            <a href="{{{url}}}" class="tw-oauth2-login-button-{{issuertype}}">
                                <img src="{{buttonimageurl}}" alt="{{name}}"/>
                            </a>
                        {{/buttonimageurl}}
                        {{^buttonimageurl}}
                        <a href="{{{url}}}" class="btn btn-default">
                            {{#iconurl}}
                                <img src="{{iconurl}}" alt="" width="24" height="24"/>
                            {{/iconurl}}
                            {{#icon}}
                                {{#context}}{{>&&template}}{{/context}}
                            {{/icon}}
                            {{name}}
                        </a>
                        {{/buttonimageurl}}
                    </div>
                {{/identityproviders}}
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
                
                $buffer .= $indent . '        <div class="subcontent potentialidps">
';
                $buffer .= $indent . '            <h6>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionE384f0e9b1fcc321a1a78dba1d43f63f($context, $indent, $value);
                $buffer .= '</h6>
';
                $buffer .= $indent . '            <div class="potentialidplist">
';
                // 'identityproviders' section
                $value = $context->find('identityproviders');
                $buffer .= $this->sectionFa5a2de73ab5ddfb9db5962ea9d0e8f1($context, $indent, $value);
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section26d48d364a0c83952a889ef447ba8495(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        if (document.getElementById(\'username\').value !== "") {
            document.getElementById(\'password\').focus();
        } else {
            document.getElementById(\'username\').focus();
        }
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
                
                $buffer .= $indent . '        if (document.getElementById(\'username\').value !== "") {
';
                $buffer .= $indent . '            document.getElementById(\'password\').focus();
';
                $buffer .= $indent . '        } else {
';
                $buffer .= $indent . '            document.getElementById(\'username\').focus();
';
                $buffer .= $indent . '        }
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section480c63216ef76e91d09d372587f79b1b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    {{#autofocusform}}
        if (document.getElementById(\'username\').value !== "") {
            document.getElementById(\'password\').focus();
        } else {
            document.getElementById(\'username\').focus();
        }
    {{/autofocusform}}
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
                
                // 'autofocusform' section
                $value = $context->find('autofocusform');
                $buffer .= $this->section26d48d364a0c83952a889ef447ba8495($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
