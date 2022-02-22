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

class behat_related_course extends behat_base {


    /**
     * @When I configure related course block under General block
     */
    public function iConfigureRateCourseGenBlock()
    {
        $page = $this->getSession()->getPage();
        $page->find("xpath", "//*[contains(@data-block,'related_courses')]//..//ul[1]//li[2]//a/span[1]")->Click();
        $page->find("xpath", "//*[contains(@data-block,'related_courses')]//..//ul[2]//li[1]")->Click();
        $page->find("xpath", "//a[text()='General block settings']")->Click();
        sleep(2);
        $page->find("xpath", "//label[text()='View mode ']")->isVisible();
        $page->find("xpath", "//label[text()='View all url ']")->isVisible();
        $page->find("xpath", "//label[text()='Course display ']")->isVisible();
        $page->find("xpath", "//label[text()='Show courses based on ']")->isVisible();
        $page->find("xpath", "//label[text()='Courses are tagged using ']")->isVisible();
        $page->find("xpath", "//label[text()='Courses will be sorted by ']")->isVisible();
        $page->find("xpath", "//label[text()='Maximum number of courses to display ']")->isVisible();
        $page->find("xpath", "//input[@id='id_submitbutton']")->Click();
        sleep(2);

    }
}


