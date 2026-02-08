<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     media_mediasite
 * @category    admin
 * @copyright   2025 Andrew Rowatt <A.J.Rowatt@massey.ac.nz>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext(
        'media_mediasite/basemediasiteurl',
        new lang_string('basemediasiteurl', 'media_mediasite'),
        new lang_string('basemediasiteurl_desc', 'media_mediasite'),
        'domain.org/mediasite',
        PARAM_TEXT,
        80
    ));

    $settings->add(new admin_setting_configtext(
        'media_mediasite/authorization',
        new lang_string('authorization', 'media_mediasite'),
        new lang_string('authorization_desc', 'media_mediasite'),
        '',
        PARAM_RAW,
        80
    ));

    $settings->add(new admin_setting_configtext(
        'media_mediasite/sfapikey',
        new lang_string('sfapikey', 'media_mediasite'),
        new lang_string('sfapikey_desc', 'media_mediasite'),
        '',
        PARAM_RAW,
        80
    ));

    $settings->add(new admin_setting_configcheckbox(
        'media_mediasite/useauthorizationtickets',
        new lang_string('useauthorizationtickets', 'media_mediasite'),
        new lang_string('useauthorizationtickets_desc', 'media_mediasite'),
        1
    ));
}
