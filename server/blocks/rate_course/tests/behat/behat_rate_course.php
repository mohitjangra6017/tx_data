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
    Behat\Mink\Element\NodeElement;

class behat_rate_course extends behat_base {


    /**
     * Click the button in rate course block
     *
     * @Given /^I click button "([^"]+)" in rate course block$/
     */
    public function i_click_rate_course_button($buttonname) {
        $class = $this->get_button_class($buttonname);
        if (!$class) {
            throw new ExpectationException('"'.$buttonname.'" does not exist in rate course block', $this->getSession());
        }
        $node = $this->find('css', '.'.$class);
        $i = $node->find('css', 'i.fa');
        $node->click();
        $this->wait_for_ajax();
    }

    /**
     * Assert that the like course button is selected
     *
     * @Given /^I should see the button "([^"]+)" selected$/
     */
    public function i_should_see_like_course_selected($buttonname) {
        $this->check_button_selection($buttonname, true);
    }

    /**
     * Assert that the like course button is selected
     *
     * @Given /^I should see the button "([^"]+)" deselected$/
     */
    public function i_should_see_like_course_notselected($buttonname) {
        $this->check_button_selection($buttonname, false);
    }

    /**
     * Assert that the like course button is selected
     *
     * @Given /^I should see the button "([^"]+)" disabled$/
     */
    public function i_should_see_button_disabled($buttonname) {
        $class = $this->get_button_class($buttonname);
        $node = $this->find('css', '.'.$class);
        $button = $node->find('css', 'button');
        if ($button->getAttribute('disabled')===null) {
            throw new ExpectationException("$buttonname isn't disabled as expected", $this->getSession());
        }
    }

    /**
     * Set the course rating to a number of stars
     *
     * @Given /^I set the course rating to "([^"]+)" stars$/
     */
    public function i_set_the_course_rating($starnum) {
        $position = $starnum*20;    // one star is around 20 pixels
        if ($starnum==5) {
            $position+=10;
        }
        $this->getSession()->executeScript("$('#rating').data().rating.setStars($position)");
    }

    /**
     * Check if the number of stars highlighted is correct
     *
     * @Given /^I should see "([^"]+)" stars highlighted$/
     */
    public function i_should_see_stars_highlighted($starnum) {
        $node = $this->find('css', '.rate-course-rate-course .rating-stars');
        $this->check_highlighted_stars($node, $starnum);
    }

    /**
     * Check if the number of stars in the average rating is correct
     *
     * @Given /^I should see "Average rating" with "([^"]+)" stars highlighted$/
     */
    public function i_should_see_average_rating_stars_highlighted($starnum) {
        $node = $this->find('css', '.ratingavg .rating-stars');
        $this->check_highlighted_stars($node, $starnum);
    }

    private function check_highlighted_stars($node, $starnum) {
        $width = $starnum*20;
        $style = $node->getAttribute('style');
        $matches = null;
        if (preg_match('/width: ([0-9]+)%/', $style, $matches)) {
            if ($matches[1]!=$width) {
                $actualstarnum = $matches[1]/5;
                throw new ExpectationException("The number of stars is $actualstarnum, expected $starnum", $this->getSession());
            }
        } else {
            throw new ExpectationException("Cannot count star rating", $this->getSession());
        }
    }

    private function check_button_selection($buttonname, $isselected) {
        if (strtolower($buttonname)!=='like course') {
            throw new ExpectationException('Only "Like course" button can be deselected', $this->getSession());
        }
        $class = $this->get_button_class($buttonname);
        $node = $this->find('css', '.'.$class);
        if ($isselected && !$this->button_has_css_class($node, 'fa-thumbs-up')) {
            throw new ExpectationException("Like course button isn't selected as expected", $this->getSession());
        } else if (!$isselected && !$this->button_has_css_class($node, 'fa-thumbs-o-up')) {
            throw new ExpectationException("Like course button isn't deselected as expected", $this->getSession());
        }
    }

    protected function get_button_class($buttonname) {
        $class = false;
        $lcbuttonname = strtolower($buttonname);
        switch ($lcbuttonname) {
            case 'like course': $class = 'rate-course-like-course'; break;
            case 'enrol course': $class = 'rate-course-enrol-course'; break;
            case 'recommend course': $class = 'rate-course-recommend-course'; break;
            case 'rate course': $class = 'rate-course-rate-course'; break;
        }
        return $class;
    }

    protected function button_has_css_class(NodeElement $node, $class) {
        $element = $node->find('css', 'i.fa');

        if (strpos($element->getAttribute('class'), $class)===false) {
            return false;
        } else {
            return true;
        }
    }

    protected function wait_for_ajax() {
        $this->getSession()->wait(self::TIMEOUT*1000, '($.active===0)');
    }

    /**
     * @Given I delete the rating of course
     */
    public function iDeleteTheRatingOfCourse()
    {
        $page = $this->getSession()->getPage();
        $page->find("xpath", "(//p[@class='course-review-delete']//a[text()='Delete'])[1]")->press();

    }

    /**
     * @Given I recommend the course to another User
     */
    public function iRecommendTheCourse()
    {
        $page = $this->getSession()->getPage();
        $page->find("xpath", "(//div[@title='Recommend this course']//a)[1]")->Click();
        $page->find("xpath", "//a[@class='select2-choice select2-default']")->Click();
        $page->find("xpath", "//input[@id='s2id_autogen2_search']")->setValue("A");
        sleep(5);
        $page->find("xpath", "(//li[@class='select2-results-dept-0 select2-result select2-result-selectable'])[1]")->click();
        sleep(2);
        $page->find("xpath", "//button[text()='Recommend course']")->Click();

    }
    /**
     * @When I configure rate course common block
     */
    public function iConfigureRateCourseCommBlock()
    {
        $page = $this->getSession()->getPage();
        $page->find("xpath", "//*[contains(@data-dock-title,'Rate Course')]//..//ul[1]//li[2]")->Click();
        $page->find("xpath", "//*[contains(@data-dock-title,'Rate Course')]//..//ul[2]//li[1]")->Click();
        $this->assertSession()->pageTextContains($this->fixStepArgument("Block Configuration"));

        //Common Block Setting
        $this->assertSession()->checkboxNotChecked($this->fixStepArgument("cs_override_title"));
        $this->assertSession()->checkboxChecked($this->fixStepArgument("cs_enable_hiding"));
        $this->assertSession()->checkboxChecked($this->fixStepArgument("cs_enable_docking"));

        $page->find("xpath", "//input[@value='Save changes']")->Click();
    }
    /**
     * @Given I add the :arg1
     */
    public function iAddTheBlock($blockname)
    {
        if (!$this->running_javascript()) {
            throw new DriverException('Adding blocks requires JavaScript.');
        }

        $regionname ='side-pre';
        $this->execute(
            "behat_general::i_click_on_in_the",
            array('.addBlock--trigger', 'css_element', '#block-region-' . $regionname, 'css_element')
        );
        $this->execute(
            "behat_general::i_click_on",
            array(".addBlock .popover .addBlockPopover--results_list_item[data-addblockpopover-blocktitle='" . $this->escape($blockname) . "']", "css_element")
        );
}

}


