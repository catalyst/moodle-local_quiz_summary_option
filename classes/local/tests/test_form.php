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
 * Mock form for testing.
 *
 * @package    local_quiz_summary_option
 * @author     Christina Roperto (christinatheeroperto@catalyst-au.net)
 * @copyright  2021 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_quiz_summary_option\local\tests;

/**
 * Form object to be used in test case
 */
class test_form extends \moodleform {

    private $modulename;

    private $coursemodule;

    /**
     * test_form constructor.
     * @param string $modulename
     * @param int $coursemodule
     * @param null $action
     * @param null $customdata
     * @param string $method
     * @param string $target
     * @param null $attributes
     * @param bool $editable
     * @param null $ajaxformdata
     */
    public function __construct(string $modulename = 'quiz', int $coursemodule = 12345, $action = null, $customdata = null, $method = 'post', $target = '', $attributes = null, $editable = true, $ajaxformdata = null) {
        $this->modulename = $modulename;
        $this->coursemodule = $coursemodule;
        parent::__construct($action, $customdata, $method, $target, $attributes, $editable, $ajaxformdata);
    }

    /**
     * Form definition.
     */
    public function definition() {
        // No definition required.
    }
    /**
     * Returns form reference.
     * @return \stdClass
     */
    public function get_current() {
        return (object) [
          'modulename' => $this->modulename,
          'coursemodule' => $this->coursemodule,
        ];
    }
}