@media @media_mediasite @javascript
Feature: Mediasite media player embedding
  In order to include Mediasite presentation videos in my course
  As a teacher
  I need to be able to embed Mediasite URLs that are automatically converted to embedded players

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | One      | teacher1@example.com |
      | student1 | Student   | One      | student1@example.com |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  Scenario: Embed a Mediasite video link in a label
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    When I add a "Text and media area" to section "1" using the activity chooser
    And I set the following fields to these values:
      | Text | <a href="https://webcast.massey.ac.nz/Mediasite/Play/49ea8f3058be4b89be35c5d11e1866901d">Watch the video</a> |
    And I press "Save and return to course"
    Then "//iframe[contains(@src, 'webcast.massey.ac.nz/Mediasite')]" "xpath_element" should exist
