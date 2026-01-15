![Moodle Plugin CI](https://github.com/andrewrowatt-masseyuni/moodle-media_mediasite/actions/workflows/moodle-ci.yml/badge.svg)

# Mediasite Media Player

This plugin automatically recognises Mediasite presentation URLs and converts them into an embedded video.

## Description

When teachers insert Mediasite links into their courses, the plugin automatically converts these URLs into HTML iframe embed code, providing a native viewing experience without requiring students to navigate away from Moodle. In case of SSO or other HTML iframe security issues, a direct link to the video is displayed below the video.

For users with the *Teacher* role, the plugin also gives a warning if the video is marked as “Private” in Mediasite. This is an optional feature that requires access to the Mediasite API.

## Installing via uploaded ZIP file

1.  Log in to your Moodle site as an admin and go to *Site administration \> Plugins \> Install plugins*.
2.  Upload the ZIP file with the plugin code. You should only be prompted to add extra details if your plugin type is not automatically detected.
3.  Check the plugin validation report and finish the installation.

## Installing manually

The plugin can also be installed by putting the contents of this directory into

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
