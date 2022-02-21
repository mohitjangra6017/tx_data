<?php

/**
 * Step definition for rate course block testing
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2015 Nine Lanterns Pty Ltd  {@link http://www.ninelanterns.com.au}
 * @author     tri.le
 * @version    1.0
 */

require_once(__DIR__.'/../../../../lib/behat/behat_base.php');

use Behat\Behat\Context\Step\Given as Given,
    Behat\Mink\Exception\ExpectationException,
    Behat\MinkExtension\Context\MinkContext,
    Behat\Mink\Element\NodeElement;

class behat_required_learning extends behat_base {

    /**
     * @When I configure general block settings under required learning block
     */
    public function iConfigureGeneralBlockSettingsUnderRequiredLearningBlock()
    {
        $page = $this->getSession()->getPage();
        $page->find("xpath", "//*[contains(@data-block,'required_learning')]//div//div//ul[1]//li[2]")->Click();
        $page->find("xpath", "//*[contains(@data-block,'required_learning')]//div//div//ul[2]//li[1]")->Click();
        $page->find("xpath", "//a[text()='General block settings']")->isVisible();
        $page->find("xpath", "//a[text()='General block settings']")->Click();
        sleep(3);
        $select = $this->fixStepArgument("id_config_show");
        $option = $this->fixStepArgument("course");

        $this->getSession()->getPage()->selectFieldOption($select, $option);
        sleep(3);
        $page->find("xpath", "//input[@id='id_submitbutton']")->Click();

    }
    /**
     * Returns fixed step argument (with \\" replaced back to ")
     *
     * @param string $argument
     *
     * @return string
     */
    protected function fixStepArgument($argument)
    {
        return str_replace('\\"', '"', $argument);
    }

    /**
     * @Given /^I click on goto required learning link$/
     */
    public function iClickOnGotoRequiredLearningLink()
    {


    }



}


