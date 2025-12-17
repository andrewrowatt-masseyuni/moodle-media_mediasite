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

namespace media_mediasite;

use curl;

/**
 * Class helper
 *
 * @package    media_mediasite
 * @copyright  2025 Andrew Rowatt <A.J.Rowatt@massey.ac.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class util {
    /**
     * API call successful and presentation is not private
     * @var int
     */
    const PRESENTATION_IS_NOT_PRIVATE = 0;

    /**
     * API call successful and presentation is private
     * @var int
     */
    const PRESENTATION_IS_PRIVATE = 1;

    /**
     * API call not successful. Reason unknown
     * @var int
     */
    const API_ERROR = 2;

    /**
     * API call successful and presentation not found
     * @var int
     */
    const PRESENTATION_NOT_FOUND = 3;

    /**
     * Queries the API and returns the status of the presentation or if there was an API error
     * @param string $presentationid
     * @return int
     */
    public static function presentation_is_private(string $presentationid): int {
        $baseurl = get_config('media_mediasite', 'basemediasiteurl');
        $endpoint = 'https://' . $baseurl . '/api/v1/Presentations(\'' . urlencode($presentationid) . '\')?$select=full';
        $baseurl = get_config('media_mediasite', 'basemediasiteurl');
        $authorization = get_config('media_mediasite', 'authorization');
        $sfapikey = get_config('media_mediasite', 'sfapikey');

        $ch = new curl();
        $ch->setHeader([
                'Content-Type: application/json',
                "Authorization: $authorization",
                'Accept: application/json',
                "sfapikey: $sfapikey",
                'User-Agent: curl',
        ]);

        $responseraw = $ch->get($endpoint);

        if ($ch->get_errno() !== 0) {
            return self::API_ERROR;
        }

        $response = json_decode($responseraw);

        if ($response) {
            if ($response->Private) {
                return self::PRESENTATION_IS_PRIVATE;
            } else {
                return self::PRESENTATION_IS_NOT_PRIVATE;
            }
        } else {
            // ... no JSON response from Mediasite
            return self::API_ERROR;
        }
    }

    /**
     * A human-friendly label for the presentation private status
     * @param int $privatestatus
     * @return string
     */
    public static function get_status_label(int $privatestatus): string {
        switch ($privatestatus) {
            case self::PRESENTATION_IS_PRIVATE:
                return get_string('presentationisprivate', 'media_mediasite');
            case self::PRESENTATION_IS_NOT_PRIVATE:
                return get_string('presentationispublic', 'media_mediasite');
            case self::PRESENTATION_NOT_FOUND:
                return get_string('presentationnotfound', 'media_mediasite');
            default:
                return get_string('presentationstatusunknown', 'media_mediasite');
        }
    }
}
