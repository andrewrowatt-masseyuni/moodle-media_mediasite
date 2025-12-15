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

/**
 * Class helper
 *
 * @package    media_mediasite
 * @copyright  2025 Andrew Rowatt <A.J.Rowatt@massey.ac.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class util {

    
    
    const PRESENTATION_IS_NOT_PRIVATE = 0;
    const PRESENTATION_IS_PRIVATE = 1;
    const API_ERROR = 2;
    const PRESENTATION_NOT_FOUND = 3;
    

    static function presentation_is_private(string $presentationid): int {
        $baseurl = get_config('media_mediasite', 'basemediasiteurl');
        $endpoint = 'https://' . $baseurl . '/api/v1/Presentations(\'' . urlencode($presentationid) . '\')?$select=full';
        $baseurl = get_config('media_mediasite', 'basemediasiteurl');
        $authorization = get_config('media_mediasite', 'authorization');
        $sfapikey = get_config('media_mediasite', 'sfapikey');

        $ch = curl_init($endpoint);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: $authorization",
            'Accept: application/json',
            "sfapikey: $sfapikey",
            'User-Agent: curl',
        ]);

        $responseraw = curl_exec($ch);
        $response = json_decode($responseraw);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlerrorcode = curl_error($ch);
        curl_close($ch);

        // var_dump($responseraw);
        // var_dump($httpcode);

        if ($response) {
            // ... we have a JSON response from Assyst
            switch ($httpcode) {
                case 200:
                    if($response->Private) {
                        return self::PRESENTATION_IS_PRIVATE;
                    } else {
                        return self::PRESENTATION_IS_NOT_PRIVATE;
                    }
                case 401:
                    // Auth issue
                    return self::API_ERROR;
            }
        } else {
            // ... no JSON response from Mediasite
            return self::API_ERROR;
        }
        
        return self::API_ERROR;
    }

    static function get_status_label(bool $isprivate): string {
        switch ($isprivate) {
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
