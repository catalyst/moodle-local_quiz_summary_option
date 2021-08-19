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
 * Lib functions.
 *
 * @package   local_quiz_summary_option
 * @author    Christina Roperto (christinatheeroperto@catalyst-au.net)
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Implements callbacks coursemodule_standard_elements to add an option to show/hide quiz summary page.
 *
 * @param \moodleform_mod $formwrapper An instance of moodleform_mod class.
 * @param \MoodleQuickForm $mform Course module form instance.
 */
define('SUMMARY_OPTION_SHOW', 'SHOW');
define('SUMMARY_OPTION_HIDE', 'HIDE');

function local_quiz_summary_option_coursemodule_standard_elements(\moodleform_mod $formwrapper, \MoodleQuickForm $mform) {
    global $DB;

    $modulename = $formwrapper->get_current()->modulename;
    if ($modulename != 'quiz') {
        return;
    }

    $cmid = $formwrapper->get_current()->coursemodule;
    $row = $DB->get_record('local_quiz_summary_option', ['cmid' => $cmid], 'show_summary');
    $show = true;
    if ($row) {
        $show = $row->show_summary;
    }
    $default = $show ? SUMMARY_OPTION_SHOW : SUMMARY_OPTION_HIDE;

    $mform->addElement('header', 'summaryoptionhdr', get_string('summarypageoption', 'local_quiz_summary_option'));
    $mform->addElement(
        'select',
        'summaryoption',
        get_string('summaryoption', 'local_quiz_summary_option'),
        [
            SUMMARY_OPTION_SHOW => get_string('summaryoption_show', 'local_quiz_summary_option'),
            SUMMARY_OPTION_HIDE => get_string('summaryoption_hide', 'local_quiz_summary_option'),
        ]
    );
    $mform->setDefault('summaryoption', $default);
    $mform->addHelpButton('summaryoption', 'summaryoption', 'local_quiz_summary_option');
}

/**
 * Implements hook coursemodule_edit_post_actions and adding a show flag.
 *
 * @param stdClass $moduleinfo Course module object.
 * @param int $course Course ID.
 */
function local_quiz_summary_option_coursemodule_edit_post_actions($moduleinfo, $course) {
    if ($moduleinfo->modulename != 'quiz') {
        return;
    }
    $show = 1;
    $cmid = $moduleinfo->coursemodule;
    if ($moduleinfo->summaryoption == SUMMARY_OPTION_HIDE) {
        $show = 0;
    }
    global $DB;
    $row = $DB->get_record('local_quiz_summary_option', ['cmid' => $cmid], 'id');

    // Check if record exists, if yes then update otherwise insert the record.
    if ($row) {
        $DB->update_record('local_quiz_summary_option', ['id' => $row->id, 'show_summary' => $show]);
    } else {
        $DB->insert_record('local_quiz_summary_option', ['cmid' => $cmid, 'show_summary' => $show], false);
    }
}
/**
 * Checks if show summary is disabled (hidden) then skips summary page.
 */
function local_quiz_summary_option_after_config() {
    global $DB, $SCRIPT;

    if ($SCRIPT != '/mod/quiz/processattempt.php') {
        return;
    }

    $nextpage = optional_param('nextpage', 0, PARAM_INT);
    if ($nextpage != -1) {
        return;
    }

    // The $_POST['next'] =  Finish attempt... is only set when you click the button.
    // Setting the default to none to ensure that it's the only button to finish attempt.
    $thispage = optional_param('thispage', 0, PARAM_INT);
    $next = optional_param('next', null, PARAM_TEXT);
    if (is_null($next) && ($thispage != -1)) {
        return;
    }

    $cmid = optional_param('cmid', null, PARAM_INT);
    $row = $DB->get_record('local_quiz_summary_option', ['cmid' => $cmid], 'show_summary');
    if (!$row) {
        return;
    }

    $show = $row->show_summary;
    if ($show) {
        return;
    }

    $_GET['finishattempt'] = 1;
}
