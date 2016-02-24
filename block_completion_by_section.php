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
 * Section Completion Block definition
 *
 * @package    block_completion_by_section
 * @copyright  2016 Andre Yamin (andreyamin@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_completion_by_section extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_completion_by_section');
    }
 	
 	public function get_content() {
	 	global $USER, $CFG, $COURSE, $DB;	    

	 	if ($this->content !== null) {
	      return $this->content;
	    }

	    $this->content =  new stdClass;
	    $this->content->text = "";

	    $sections = $DB->get_records('course_sections', array('course'=>$COURSE->id));

	    foreach ($sections as $section) {
	    	
	    	if ($section->visible){
	    		$this->content->text .= '<p>'. get_section_name($COURSE->id, $section);
	    		$cms = $DB->get_records('course_modules', array('section'=>$section->id));
	    		$total = 0;
	    		$count = 0;
	    		foreach ($cms as $cm) {
	    			if ($cm->visible){
	    				$total ++;
	    				$cond = array('coursemoduleid'=>$cm->id, 'userid'=>$USER->id, 'completionstate'=>'1');
	    				$iscomplete = $DB->record_exists('course_modules_completion', $cond);
	    				if ($iscomplete){
	    					$count ++;
	    				}
	    			}
	    		}
	    		if ($total) {
	    			$percent = $count/$total*100;
	    		} else {
	    			$percent = 100;
	    		}

    			$this->content->text .= '<div class="progress">';
    			$this->content->text .= '<div class="bar" style="width:' . $percent . '%">';
    			$this->content->text .= '<span class="sr-only">' . $percent . '%</span></div></div>';

	    	}

	    }

	    return $this->content;
	}

}