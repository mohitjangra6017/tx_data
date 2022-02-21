
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

class behat_isotope_block extends behat_base {

    /**
     * @When I configure general block settings under isotope block
     */
    public function iConfigureGeneralBlockSettingsUnderIsotopeBlock()
    {
        $page = $this->getSession()->getPage();
        $page->find("xpath", "//*[contains(@data-block,'isotope')]//div//div//ul[1]//li[2]")->Click();
        $page->find("xpath", "//*[contains(@data-block,'isotope')]//div//div//ul[2]//li[1]")->Click();
        $page->find("xpath", "//a[text()='General block settings']")->isVisible();
        $page->find("xpath", "//a[text()='General block settings']")->Click();
        sleep(3);
        $select = $this->fixStepArgument("id_config_provider");
        $option = $this->fixStepArgument("bookings");
        $this->getSession()->getPage()->selectFieldOption($select, $option);
        sleep(2);
        $select = $this->fixStepArgument("id_config_provider");
        $option = $this->fixStepArgument("record_of_learning");
        $this->getSession()->getPage()->selectFieldOption($select, $option);
        sleep(2);
        $select = $this->fixStepArgument("id_config_provider");
        $option = $this->fixStepArgument("programs");
        $this->getSession()->getPage()->selectFieldOption($select, $option);
        sleep(2);
        $select = $this->fixStepArgument("id_config_provider");
        $option = $this->fixStepArgument("mandatory_completion");
        $this->getSession()->getPage()->selectFieldOption($select, $option);
        sleep(2);
        $select = $this->fixStepArgument("id_config_provider");
        $option = $this->fixStepArgument("required_learning");
        $this->getSession()->getPage()->selectFieldOption($select, $option);
        sleep(2);
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





}


