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
 * Tests for Mediasite
 *
 * @package    media_mediasite
 * @category   test
 * @copyright  2025 Andrew Rowatt <A.J.Rowatt@massey.ac.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class player_test extends \advanced_testcase {
    /**
     * Pre-test setup. Preserves $CFG.
     *
     * @covers \media_mediasite
     */
    public function setUp(): void {
        parent::setUp();

        // Reset $CFG and $SERVER.
        $this->resetAfterTest();

        // Consistent initial setup: all players disabled.
        \core\plugininfo\media::set_enabled_plugins('mediasite');
        $basemediasiteurl = getenv("BASEMEDIASITEURL");
        $authorization = getenv("AUTHORIZATION");
        $sfapikey = getenv("SFAPIKEY");

        set_config('basemediasiteurl', $basemediasiteurl, 'media_mediasite');
        set_config('authorization', $authorization, 'media_mediasite');
        set_config('sfapikey', $sfapikey, 'media_mediasite');
    }

    /**
     * Test that plugin is returned as enabled media plugin.
     *
     * @covers \media_mediasite
     */
    public function test_is_installed(): void {
        $sortorder = \core\plugininfo\media::get_enabled_plugins();
        $this->assertEquals(['mediasite' => 'mediasite'], $sortorder);
    }

    /**
     * Test that mediaplugin filter replaces a link to the supported file with media tag.
     *
     * filter_mediaplugin is enabled by default.
     *
     * @covers \media_mediasite
     */
    public function test_embed_link(): void {
        global $CFG;

        $this->setAdminUser();

        $url = new \moodle_url('https://webcast.massey.ac.nz/Mediasite/Play/49ea8f3058be4b89be35c5d11e1866901d');
        $text = \html_writer::link($url, 'Lorem ipsum dolor sit amet');
        $content = format_text($text, FORMAT_HTML);

        $this->assertMatchesRegularExpression('~media_mediasite~', $content);
        $this->assertMatchesRegularExpression('~</iframe>~', $content);
        $this->assertMatchesRegularExpression('~width="' . $CFG->media_default_width . '" height="' .
            $CFG->media_default_height . '"~', $content);
        $this->assertMatchesRegularExpression('~Presentation is currently private~', $content);

        $url = new \moodle_url('https://webcast.massey.ac.nz/Mediasite/Play/fb1b6a3187754c17af1b399e734a22b51d');
        $text = \html_writer::link($url, 'Lorem ipsum dolor sit amet');
        $content = format_text($text, FORMAT_HTML);

        $this->assertMatchesRegularExpression('~media_mediasite~', $content);
        $this->assertMatchesRegularExpression('~</iframe>~', $content);
        $this->assertMatchesRegularExpression('~width="' . $CFG->media_default_width . '" height="' .
            $CFG->media_default_height . '"~', $content);
        $this->assertDoesNotMatchRegularExpression('~Presentation is currently private~', $content);
    }
}
