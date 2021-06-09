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
 * Event observer
 *
 * @package   local_quiz_summary_option
 * @author    Christina Roperto (christinatheeroperto@catalyst-au.net)
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_quiz_summary_option;

defined('MOODLE_INTERNAL') || die();

/**
 * Class event_observer for all related event observers.
 *
 * @package local_quiz_summary_option
 */

class event_observer {
/*    public static function redirect_after_attempt_started(\mod_quiz\event\attempt_started $event) {
        var_dump($event);
        die("event observer");
    }*/
    public static function redirect_after_user_updated($event) {
        var_dump($event);
    }

}

