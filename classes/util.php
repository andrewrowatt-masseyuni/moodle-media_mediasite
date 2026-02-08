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
     * Makes an authenticated request to the Mediasite API.
     * @param string $path The API path (appended to /api/v1/).
     * @param string $method The HTTP method ('get' or 'post').
     * @param string|null $body The request body for POST requests.
     * @return object|null The decoded JSON response, or null on failure.
     */
    private static function api_request(string $path, string $method = 'get', ?string $body = null): ?object {
        $baseurl = get_config('media_mediasite', 'basemediasiteurl');
        $endpoint = 'https://' . $baseurl . '/api/v1/' . $path;
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

        $responseraw = ($method === 'post') ? $ch->post($endpoint, $body) : $ch->get($endpoint);

        if ($ch->get_errno() !== 0) {
            return null;
        }

        $info = $ch->get_info();

        if ($info['http_code'] != 200) {
            return null;
        }

        return json_decode($responseraw) ?: null;
    }

    /**
     * Queries the API and returns the status of the presentation or if there was an API error
     * @param string $presentationid
     * @return int
     */
    public static function presentation_is_private(string $presentationid): int {
        $response = self::api_request('Presentations(\'' . urlencode($presentationid) . '\')?$select=full');

        if (!$response) {
            return self::API_ERROR;
        }

        return $response->Private ? self::PRESENTATION_IS_PRIVATE : self::PRESENTATION_IS_NOT_PRIVATE;
    }

    /**
     * Requests an authorization ticket from the Mediasite API for the given presentation.
     * @param string $presentationid
     * @return string The authorization ticket string, or empty string on failure.
     */
    public static function get_authorization_ticket(string $presentationid): string {
        global $USER;

        $body = json_encode([
            'ResourceId' => $presentationid,
            'Username' => $USER->username,
            'MinutesToLive' => '300',
        ]);

        $response = self::api_request('AuthorizationTickets', 'post', $body);

        return $response->TicketId ?? '';
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
