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

class behat_record_of_learning extends behat_base {


    /**
     * @Then /^I Check Active Course Tab Functionality$/
     */
    public function iCheckActiveCourseTabFunctionality()
    {
        $page = $this->getSession()->getPage();
        //Active Course Tab
        $page->find("xpath", "//li[@class='active']//a[text()='Courses']")->isVisible();
        //Search By
        $page->find("xpath", "//a[text()='Search by']")->isVisible();
        //Show/Hide Column
        $page->find("xpath", "//input[@value='Show/Hide Columns']")->isVisible();
        //Course 1 is Visible
        $page->find("xpath", "//a[text()='Course 1']")->isVisible();

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


