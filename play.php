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
 * TODO describe file play
 *
 * @package    media_mediasite
 * @copyright  2026 Andrew Rowatt <A.J.Rowatt@massey.ac.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_once($CFG->libdir . '/filelib.php');

require_login();

$presentationid = required_param('id', PARAM_ALPHANUMEXT);
$baseurl = get_config('media_mediasite', 'basemediasiteurl');
$playurl = 'https://' . $baseurl . '/play/' . $presentationid;

if (get_config('media_mediasite', 'useauthorizationtickets')) {
    $ticketid = media_mediasite\util::get_authorization_ticket($presentationid);
    if ($ticketid) {
        $playurl .= '?authTicket=' . $ticketid;
    }
}

redirect($playurl);
