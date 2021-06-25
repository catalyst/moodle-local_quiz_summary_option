<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Test lib functions
 *
 * @package    local_quiz_summary_option
 * @author     Christina Roperto (christinatheeroperto@catalyst-au.net)
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

class local_quiz_summary_option_lib_testcase extends advanced_testcase {

    /**
     * Test if summary option element is added on the form
     */
    public function test_quiz_summary_option_element_is_added() {
        // Given: assumptions.
        $current = new stdClass();
        $current->modulename = 'quiz';
        $current->coursemodule = 12345;
        $mockbuilder = $this->getMockBuilder(moodleform_mod::class);
        $mockbuilder->disableOriginalConstructor();
        $mockbuilder->setMethods(['get_current', 'definition']);
        $formwrapperstub = $mockbuilder->getMock();
        $formwrapperstub->method('get_current')->willReturn($current);
        $mform = new MoodleQuickForm('test', 'POST', 'test');
        // When: action to test.
        local_quiz_summary_option_coursemodule_standard_elements($formwrapperstub, $mform);
        // Then: what you expect.
        $found = $mform->getElement('summaryoption');
        self::assertInstanceOf(MoodleQuickForm_select::class, $found);
    }

    /**
     * Test if module name is not quiz, dont show the summary option element.
     */
    public function test_quiz_summary_option_ignores_if_module_not_quiz() {
        // Given: assumptions.
        $current = new stdClass();
        $current->modulename = 'forum';
        $current->coursemodule = 12345;
        $mockbuilder = $this->getMockBuilder(moodleform_mod::class);
        $mockbuilder->disableOriginalConstructor();
        $mockbuilder->setMethods(['get_current', 'definition']);
        $formwrapperstub = $mockbuilder->getMock();
        $formwrapperstub->method('get_current')->willReturn($current);
        $mform = new MoodleQuickForm('test', 'POST', 'test');
        // When: action to test.
        local_quiz_summary_option_coursemodule_standard_elements($formwrapperstub, $mform);
        // Then: what you expect.
        $found = $mform->elementExists('summaryoption');
        self::assertFalse($found);
    }

    /**
     * Test if dropdown select will default to the option chosen. In this case if show is chosen, will default to show.
     */
    public function test_if_saved_show_it_defaults_to_show() {
        $current = new stdClass();
        $current->modulename = 'quiz';
        $current->coursemodule = 12345;
        $mockbuilder = $this->getMockBuilder(moodleform_mod::class);
        $mockbuilder->disableOriginalConstructor();
        $mockbuilder->setMethods(['get_current']);
        $formwrapperstub = $mockbuilder->getMock();
        $formwrapperstub->method('get_current')->willReturn($current);
        $mform = new MoodleQuickForm('test', 'POST', 'test');
        local_quiz_summary_option_coursemodule_standard_elements($formwrapperstub, $mform);
        self::assertEquals('SHOW', $mform->_defaultValues['summaryoption']);
    }

    /**
     * Test if dropdown select will default to the option chosen. In this case if hide is chosen, will default to hide.
     */
    public function test_if_saved_hide_it_defaults_to_hide() {
        global $DB;
        $this->resetAfterTest(true);

        $current = new stdClass();
        $current->modulename = 'quiz';
        $current->coursemodule = 12345;
        $mockbuilder = $this->getMockBuilder(moodleform_mod::class);
        $mockbuilder->disableOriginalConstructor();
        $mockbuilder->setMethods(['get_current']);
        $formwrapperstub = $mockbuilder->getMock();
        $formwrapperstub->method('get_current')->willReturn($current);
        $mform = new MoodleQuickForm('test', 'POST', 'test');
        $DB->insert_record('local_quiz_summary_option', ['cmid' => $current->coursemodule, 'show_summary' => 0], false);

        local_quiz_summary_option_coursemodule_standard_elements($formwrapperstub, $mform);

        self::assertEquals('HIDE', $mform->_defaultValues['summaryoption']);
    }

    /**
     * Test if DB will call insert method.
     */
    public function test_post_inserts_as_needed() {
        global $DB;
        $this->resetAfterTest(true);

        $moduleinfo = new stdClass();
        $moduleinfo->modulename = 'quiz';
        $moduleinfo->coursemodule = '12345';
        $moduleinfo->summaryoption = 'SHOW';
        $course = $this->getDataGenerator()->create_course('55555');

        local_quiz_summary_option_coursemodule_edit_post_actions($moduleinfo, $course);

        $row = $DB->get_record('local_quiz_summary_option', ['cmid' => $moduleinfo->coursemodule], 'show_summary');
        self::assertEquals(1, $row->show_summary);
    }

    /**
     * Test if DB will call update method.
     */
    public function test_post_updates_as_needed() {
        global $DB;
        $this->resetAfterTest(true);

        $moduleinfo = new stdClass();
        $moduleinfo->modulename = 'quiz';
        $moduleinfo->coursemodule = '12345';
        $moduleinfo->summaryoption = 'SHOW';
        $course = $this->getDataGenerator()->create_course('55555');

        $DB->insert_record('local_quiz_summary_option', ['cmid' => $moduleinfo->coursemodule, 'show_summary' => 0], false);

        local_quiz_summary_option_coursemodule_edit_post_actions($moduleinfo, $course);

        $row = $DB->get_record('local_quiz_summary_option', ['cmid' => $moduleinfo->coursemodule], 'show_summary');
        self::assertEquals(1, $row->show_summary);
    }

    /**
     * Test if DB will ignore insertion if module name is not quiz
     */
    public function test_post_ignores_if_not_quiz() {
        global $DB;
        $this->resetAfterTest(true);

        $moduleinfo = new stdClass();
        $moduleinfo->modulename = 'forum';
        $moduleinfo->coursemodule = '12345';
        $moduleinfo->summaryoption = 'SHOW';

        local_quiz_summary_option_coursemodule_edit_post_actions($moduleinfo, null);

        $row = $DB->get_record('local_quiz_summary_option', ['cmid' => $moduleinfo->coursemodule], 'id');
        self::assertEquals(false, $row);
    }

    /**
     * Test if no option was chosen/saved previously, will default to show
     */
    public function test_shows_summary_page_by_default() {
        global $SCRIPT;
        $SCRIPT = '/mod/quiz/processattempt.php';
        $_GET['nextpage'] = -1;

        local_quiz_summary_option_after_config();

        self::assertArrayNotHasKey('finishattempt', $_GET);
    }

    /**
     * Test if 'hide' was chosen, summary page will be hidden.
     */
    public function test_hides_summary_page_if_hide() {
        global $DB, $SCRIPT;
        $this->resetAfterTest(true);

        $SCRIPT = '/mod/quiz/processattempt.php';
        $_GET['nextpage'] = -1;
        $_GET['cmid'] = 12345;

        $DB->insert_record('local_quiz_summary_option', ['cmid' => $_GET['cmid'], 'show_summary' => 0], false);

        local_quiz_summary_option_after_config();

        self::assertArrayHasKey('finishattempt', $_GET);
        self::assertEquals(1, $_GET['finishattempt']);

    }

    /**
     * Test if 'show' was chosen, summary page will be shown.
     */
    public function test_not_shows_summary_page_if_show() {
        global $DB, $SCRIPT;
        $this->resetAfterTest(true);

        $SCRIPT = '/mod/quiz/processattempt.php';
        $_GET['nextpage'] = -1;
        $_GET['cmid'] = 12345;

        $DB->insert_record('local_quiz_summary_option', ['cmid' => $_GET['cmid'], 'show_summary' => 1], false);

        local_quiz_summary_option_after_config();

        self::assertArrayNotHasKey('finishattempt', $_GET);
    }
}
