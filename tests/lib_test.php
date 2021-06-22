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

class local_quiz_summary_option_lib_testcase extends advanced_testcase {
    public function test_quiz_summary_option_element_is_added() {
        // Given: assumptions.
        $current = new stdClass();
        $current->modulename = "quiz";
        $current->coursemodule = 12345;
        $mockbuilder = $this->getMockBuilder(moodleform_mod::class);
        $mockbuilder->disableOriginalConstructor();
        $formwrapperstub = $mockbuilder->getMock();
        $formwrapperstub->method('get_current')->willReturn($current);
        $mform = new MoodleQuickForm("test", "POST", "test");
        // When: action to test.
        local_quiz_summary_option_coursemodule_standard_elements($formwrapperstub, $mform);
        // Then: what you expect.
        $found = $mform->getElement("summaryoption");
        self::assertInstanceOf(MoodleQuickForm_select::class, $found);
    }

    public function test_quiz_summary_option_ignores_if_module_not_quiz() {
        // Given: assumptions.
        $current = new stdClass();
        $current->modulename = "forum";
        $current->coursemodule = 12345;
        $mockbuilder = $this->getMockBuilder(moodleform_mod::class);
        $mockbuilder->disableOriginalConstructor();
        $formwrapperstub = $mockbuilder->getMock();
        $formwrapperstub->method('get_current')->willReturn($current);
        $mform = new MoodleQuickForm("test", "POST", "test");
        // When: action to test.
        local_quiz_summary_option_coursemodule_standard_elements($formwrapperstub, $mform);
        // Then: what you expect.
        $found = $mform->elementExists("summaryoption");
        self::assertFalse($found);
    }

    public function test_if_saved_show_it_defaults_to_show() {
        global $DB;
        $current = new stdClass();
        $current->modulename = "quiz";
        $current->coursemodule = 12345;
        $mockbuilder = $this->getMockBuilder(moodleform_mod::class);
        $mockbuilder->disableOriginalConstructor();
        $formwrapperstub = $mockbuilder->getMock();
        $formwrapperstub->method('get_current')->willReturn($current);
        $mform = new MoodleQuickForm("test", "POST", "test");

        $dbmockbuilder = $this->getMockBuilder(moodle_database::class);
        $dbmockbuilder->disableOriginalConstructor();
        $DB = $dbmockbuilder->getMock();
        $row = new stdClass();
        $row->show_summary = 1;
        $row->id = '11111';
        $DB->method('get_record')->willReturn($row);
        local_quiz_summary_option_coursemodule_standard_elements($formwrapperstub, $mform);
        self::assertEquals('SUMMARY_OPTION_SHOW', $mform->_defaultValues['summaryoption']);
    }

    public function test_if_saved_hide_it_defaults_to_hide() {
        global $DB;
        $current = new stdClass();
        $current->modulename = "quiz";
        $current->coursemodule = 12345;
        $mockbuilder = $this->getMockBuilder(moodleform_mod::class);
        $mockbuilder->disableOriginalConstructor();
        $formwrapperstub = $mockbuilder->getMock();
        $formwrapperstub->method('get_current')->willReturn($current);
        $mform = new MoodleQuickForm("test", "POST", "test");

        $dbmockbuilder = $this->getMockBuilder(moodle_database::class);
        $dbmockbuilder->disableOriginalConstructor();
        $DB = $dbmockbuilder->getMock();
        $row = new stdClass();
        $row->show_summary = 0;
        $row->id = '11111';
        $DB->method('get_record')->willReturn($row);
        local_quiz_summary_option_coursemodule_standard_elements($formwrapperstub, $mform);
        self::assertEquals('SUMMARY_OPTION_HIDE', $mform->_defaultValues['summaryoption']);
    }

    public function test_post_inserts_as_needed() {
        global $DB;
        $moduleinfo = new stdClass();
        $moduleinfo->modulename = 'quiz';
        $moduleinfo->coursemodule = '12345';
        $moduleinfo->summaryoption = 'SUMMARY_OPTION_SHOW';
        $course = '55555';
        $mockbuilder = $this->getMockBuilder(moodle_database::class);
        $mockbuilder->disableOriginalConstructor();

        $DB = $mockbuilder->getMock();
        $DB->expects($this->once())->method('insert_record');

        local_quiz_summary_option_coursemodule_edit_post_actions($moduleinfo, $course);
    }

    public function test_post_updates_as_needed() {
        global $DB;
        $moduleinfo = new stdClass();
        $moduleinfo->modulename = 'quiz';
        $moduleinfo->coursemodule = '12345';
        $moduleinfo->summaryoption = 'SUMMARY_OPTION_SHOW';
        $course = '55555';
        $mockbuilder = $this->getMockBuilder(moodle_database::class);
        $mockbuilder->disableOriginalConstructor();

        $DB = $mockbuilder->getMock();
        $row = new stdClass();
        $row->id = 11111;
        $DB->method('get_record')->willReturn($row);
        $DB->expects($this->once())->method('update_record');

        local_quiz_summary_option_coursemodule_edit_post_actions($moduleinfo, $course);
    }

    public function test_post_ignores_if_not_quiz() {
        global $DB;
        $moduleinfo = new stdClass();
        $moduleinfo->modulename = 'forum';
        $moduleinfo->coursemodule = '12345';
        $moduleinfo->summaryoption = 'SUMMARY_OPTION_SHOW';
        $course = '55555';
        $mockbuilder = $this->getMockBuilder(moodle_database::class);
        $mockbuilder->disableOriginalConstructor();

        $DB = $mockbuilder->getMock();
        $DB->expects($this->never())->method('insert_record');

        local_quiz_summary_option_coursemodule_edit_post_actions($moduleinfo, $course);
    }

    public function test_shows_summary_page_by_default() {
        global $DB, $SCRIPT;
        $SCRIPT = '/mod/quiz/processattempt.php';
        $_GET['nextpage'] = -1;

        $mockbuilder = $this->getMockBuilder(moodle_database::class);
        $mockbuilder->disableOriginalConstructor();
        $DB = $mockbuilder->getMock();
        $row = false;

        $DB->method('get_record')->willReturn($row);

        local_quiz_summary_option_after_config();
        self::assertArrayNotHasKey("finishattempt", $_GET);
    }

    public function test_shows_summary_page_if_hide() {
        global $DB, $SCRIPT;
        $SCRIPT = '/mod/quiz/processattempt.php';
        $_GET['nextpage'] = -1;

        $mockbuilder = $this->getMockBuilder(moodle_database::class);
        $mockbuilder->disableOriginalConstructor();
        $DB = $mockbuilder->getMock();
        $row = new stdClass();
        $row->show_summary = 0;

        $DB->method('get_record')->willReturn($row);

        local_quiz_summary_option_after_config();
        self::assertArrayHasKey("finishattempt", $_GET);
        self::assertEquals(1, $_GET['finishattempt']);

    }

    public function test_not_shows_summary_page_if_show() {
        global $DB, $SCRIPT;
        $SCRIPT = '/mod/quiz/processattempt.php';
        $_GET['nextpage'] = -1;

        $mockbuilder = $this->getMockBuilder(moodle_database::class);
        $mockbuilder->disableOriginalConstructor();
        $DB = $mockbuilder->getMock();
        $row = new stdClass();
        $row->show_summary = 1;

        $DB->method('get_record')->willReturn($row);

        local_quiz_summary_option_after_config();
        self::assertEquals(1, $row->show_summary);
    }
}
