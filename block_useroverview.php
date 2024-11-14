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
 * Block definition class for the block_useroverview plugin.
 *
 * @package   block_useroverview
 * @copyright Pamal Mangat
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

        global $OUTPUT, $USER, $PAGE, $DB, $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        if (isset($this->content)) {
            return $this->content;
        }

        $this->content = new stdClass();

        // Extract # of courses that the user is enrolled in (only visible courses with an active enrolment status). 
        if (count(enrol_get_users_courses($USER->id, true)) == 0) {
            $usercoursecount = get_string('viewcoursesempty', 'block_useroverview');
            $usercourselink = false;
        } else {
            // If no enrolled courses. 
            $usercoursecount = get_string('viewcourses', 'block_useroverview', count(enrol_get_users_courses($USER->id, true)));
            $usercourselink = true;
        }

        // Get user's profile picture. 
        $userpicture = new user_picture($DB->get_record('user', array('id' => $USER->id)));
        $pictureurl = $userpicture->get_url($PAGE, $OUTPUT);

        // User data to display & pass to Mustache Renderer template.
        $userdata = [];
        $userdata['fullname'] = $USER->firstname . " " . $USER->lastname;
        $userdata['email'] = $USER->email;
        $userdata['coursecount'] = $usercoursecount;
        $userdata['mycoursesurl'] = $CFG->wwwroot . '/my/courses.php';
        $userdata['validcourseslink'] = $usercourselink; 
        $userdata['profilepictureurl'] = $userpicture->get_url($PAGE, $OUTPUT);
        $userdata['profileurl'] = new moodle_url('http://localhost/moodle41/user/edit.php?id=' . $USER->id . '&returnto=profile'); 

        // TO-DO: Add user data for: Account creation date, first login, last login displayed in user's timezone. 
        // TO-DO: Add btn to link to local plugin page with dependencies. 

        // Send user data to Moustache Render Template. 
        $this->content->text = $OUTPUT->render_from_template('block_useroverview/default', $userdata);

        return $this->content;
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
            'course-view' => false,
            'mod' => false,
            'my' => true,
        ];
    }
}