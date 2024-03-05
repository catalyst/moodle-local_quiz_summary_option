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

/**
 * Define all the restore steps that will be used by the backup_local_quiz_summary_option_plugin
 */
class restore_local_quiz_summary_option_plugin extends restore_local_plugin {
    protected function define_module_plugin_structure() {
        $paths = [];

        // This will call a function that starts with process_{name}. In this case it's calling process_quiz_summary function.
        $paths[] = new restore_path_element('quiz_summary', $this->get_pathfor(''));
        return $paths;
    }

    public function process_quiz_summary($data) {
        global $DB;

        $data['cmid'] = $this->task->get_moduleid();
        $DB->insert_record('local_quiz_summary_option', $data, false);
    }
}
