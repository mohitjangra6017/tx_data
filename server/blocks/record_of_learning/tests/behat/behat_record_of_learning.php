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



    /**
     * @Then /^I search "([^"]*)" Course$/
     */
    public function iSearchCourse($arg1)
    {
        $page = $this->getSession()->getPage();
        //Active Course Tab
        $select = $this->fixStepArgument("id_course-courselink_op");
        $option = $this->fixStepArgument("0");
        $this->getSession()->getPage()->selectFieldOption($select, $option);
        $this->find("xpath","//input[@id='id_course-courselink']")->setValue($arg1);
        $this->find("xpath","//input[@id='id_submitgroupstandard_addfilter']")->click();
    }

    /**
     * @Then I download format file
     */
    public function iDownloadFormatFile()
    {
        $page = $this->getSession()->getPage();
        $externsion = array("csv", "xlsx", "ods", "pdf", "pdf");
        $actual_rows = $page->findAll('xpath', "//div//label[text()='Export format']//..//select//option");
        $actual_Text = array();
        $i = 0;
        foreach ($actual_rows as $row) {
            $actual_Text[] = $row->getAttribute("value");
            echo $actual_Text[$i] . "\n";
            $abc = "//div//label[text()='Export format']//..//select//option[@value='$actual_Text[$i]']" . "\n";
            echo $abc . "\n";
            $xpathforclick = $this->getSession()->getPage()->find('xpath', "$abc")->press();
            echo "select format success" . "\n";
            $export_button = $this->getSession()->getPage()->find('xpath', "//input[@value='Export']")->click();
            ++$i;
        }}

        /**
         * @Then I Verify exist file then delete
         */
        public function iVerifyExistFileAndDelete()
    {


        $extension = array("csv", "xlsx", "ods", "pdf", "pdf");
        $j = 0;
        foreach ($extension as $filename) {
            echo $extension[$j] . "\n";
            $filename = "C:\Users\Mohit\Downloads\\record_of_learning_courses_report.$extension[$j]" . "\n";
            echo $filename . "\n";
            if (file_exists($filename)) {
                echo "The file $filename exists" . "\n";
            } else {
                echo "The file $filename does not exist" . "\n";
            }
            sleep(3);
            if (unlink($filename)) {
                echo 'The file ' . $filename . ' was deleted successfully!' . "\n";
            } else {
                echo 'There was a error deleting the file ' . $filename . "\n";
            }
            ++$j;
        }

    }


}


