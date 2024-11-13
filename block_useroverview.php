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
 * Block definition class for the block_pluginname plugin.
 *
 * @package   block_useroverview
 * @copyright PamalM
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_useroverview extends block_base {

    /**
     * Initialises the block.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_useroverview');
    }

    /**
     * Gets the block contents.
     *
     * @return string The block HTML.
     */
    public function get_content() {

        global $OUTPUT, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        if (isset($this->content)) {
            return $this->content;
        }

        $this->content = new stdClass();
        $data = [];
        $data['auth'] = $USER->auth;
        $data['username'] = $USER->username;
        $data['firstname'] = $USER->firstname;
        $data['lastname'] = $USER->lastname;
        $data['email'] = $USER->email;
        $data['city'] = $USER->city;
        $data['lastlogin'] = $USER->lastlogin; //to-do conver to user timezone. 
        $data['firstaccess'] = $USER->firstaccess; //to-do convert to user timezone. 

        $contextblock = enrol_get_all_users_courses(2); // testing
        echo userdate(var_dump($contextblock));

        $this->content->text = $OUTPUT->render_from_template('block_useroverview/default', $data);
        $this->content->footer = '';

        return $this->content;
    }

    /**
     * Prevent multiple instances of block being added to context(s).
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Defines in which pages this block can be added.
     *
     * @return array of the pages where the block can be added.
     */
    public function applicable_formats() {
        return [
            'admin' => false,
            'site-index' => true,
            'course-view' => true,
            'mod' => false,
            'my' => true,
        ];
    }
}