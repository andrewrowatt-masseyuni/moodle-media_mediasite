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
 * Class plugin
 *
 * @package    media_mediasite
 * @copyright  2025 Andrew Rowatt <A.J.Rowatt@massey.ac.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class media_mediasite_plugin  extends \core_media_player_external {

    const TYPE_PRESENTATION = 1;
    const TYPE_CHANNEL = 2;
    protected $type;

    public function list_supported_urls(array $urls, array $options = []) {
        // These only work with a SINGLE url (there is no fallback).
        if (count($urls) == 1) {
            $url = reset($urls);

            // Check against regex.
            if (preg_match($this->get_regex_presentation(), $url->out(false), $this->matches)) {
                $type = self::TYPE_PRESENTATION;
                return [$url];
            }
        }

        return [];
    }
    /**
     * @inheritDoc
     */
    protected function embed_external(\moodle_url $url, $name, $width, $height, $options) {
        global $OUTPUT, $PAGE;

        $info = trim($name ?? '');
        if (empty($info) or strpos($info, 'http') === 0) {
            $info = get_string('pluginname', 'media_mediasite');
        }
        $info = s($info);

        self::pick_video_size($width, $height);

        $videoid = end($this->matches);

        // Template context.
        $context = [
            'width' => $width,
            'height' => $height,
            'title' => $info,
            'courseid' => $PAGE->course->id,
            'presentationid' => $videoid,
        ];

        return $OUTPUT->render_from_template('media_mediasite/presentation', $context);
    }

    /**
     * Returns regular expression used to match URLs for single youtube video
     * @return string PHP regular expression e.g. '~^https?://example.org/~'
     */
    protected function get_regex_presentation() {
        $baseurl = preg_quote(get_config('media_mediasite', 'basemediasiteurl'));

        // Initial part of link.
        $start = "~^https?://$baseurl/";

        // Middle bit: presentation.
        $middle = 'Mediasite/Play/([a-z0-9]{34})';
        return $start . $middle . \core_media_player_external::END_LINK_REGEX_PART;
    }

    public function get_embeddable_markers() {
        $baseurl = get_config('media_mediasite', 'basemediasiteurl');

        return [$baseurl];
    }

    /**
     * Default rank
     * @return int
     */
    public function get_rank() {
        return 1001;
    }
}
