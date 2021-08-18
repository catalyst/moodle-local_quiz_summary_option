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


class backup_local_quiz_summary_option_plugin extends backup_local_plugin
{
    protected function define_module_plugin_structure() {
        // Create XML child element.
        $element_name = $this->get_recommended_name();
        $sqlcmid = backup_helper::is_sqlparam($this->get_setting_value(backup::VAR_MODID));
        $quiz_summary = new backup_nested_element($element_name, array(), array('show_summary'));
        $quiz_summary->set_source_table('local_quiz_summary_option', array('cmid' => $sqlcmid));

        // Add child to module XML.
        $plugin = $this->get_plugin_element();
        $plugin->add_child($quiz_summary);

        return $plugin;
    }
}