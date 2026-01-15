[![Moodle Plugin CI](https://github.com/andrewrowatt-masseyuni/moodle-media_mediasite/actions/workflows/moodle-ci.yml/badge.svg)](https://github.com/andrewrowatt-masseyuni/moodle-media_mediasite/actions/workflows/moodle-ci.yml)
# Mediasite Media Player

A Moodle media player plugin that enables embedding Mediasite videos directly in course content using standard Mediasite presentation URLs.

## Description

The Mediasite Media Player plugin provides seamless integration between Moodle and Mediasite video content. This media player plugin automatically recognizes Mediasite presentation URLs and embeds them as playable videos directly within Moodle course content. When instructors or content creators insert Mediasite links into their courses, the plugin automatically converts these URLs into embedded video players, providing a native viewing experience without requiring students to navigate away from the Moodle environment.

For instructors with course management capabilities, the plugin also displays the privacy status of embedded videos, warning if a video is set to private and won't be viewable by students.

**Note**: This plugin requires access to a Mediasite server. You will need to configure the base Mediasite URL and API credentials in the plugin settings.

## Installing via uploaded ZIP file

1.  Log in to your Moodle site as an admin and go to *Site administration \> Plugins \> Install plugins*.
2.  Upload the ZIP file with the plugin code. You should only be prompted to add extra details if your plugin type is not automatically detected.
3.  Check the plugin validation report and finish the installation.

## Installing manually

The plugin can be also installed by putting the contents of this directory to

```
{your/moodle/dirroot}/media/player/mediasite
```

Afterwards, log in to your Moodle site as an admin and go to *Site administration \> Notifications* to complete the installation.

Alternatively, you can run

```
$ php admin/cli/upgrade.php
```

to complete the installation from the command line.

## License

2025 Andrew Rowatt [A.J.Rowatt@massey.ac.nz](mailto:A.J.Rowatt@massey.ac.nz)

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <https://www.gnu.org/licenses/>.
