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

// This activity has not particular settings but the inherited from the generic
// backup_activity_task so here there isn't any class definition, like the ones
// existing in /backup/moodle2/backup_settingslib.php (activities section)

/**
 * Define all the backup steps that will be used by the backup_local_quiz_summary_option_plugin
 */

defined('MOODLE_INTERNAL') || die;


class backup_local_quiz_summary_option_plugin extends backup_local_plugin {


   protected function define_module_plugin_structure() {
       global $DB;

       $cmid = $this->task->get_moduleid();
       list($course, $cm) = get_course_and_cm_from_cmid($cmid);
        //print_r($cm->modname);

        if ($cm->modname!= 'quiz') {
           return;
        }
       $row = $DB->get_record('local_quiz_summary_option', ['cmid' => $cmid], 'show_summary');
       $show = true;
       if ($row) {
           $show = $row->show_summary;
       }
       print("Save in backup for CMID=$cmid is local_quiz_summary_option=$show\n");

   }
}