**Tested on Moodle 2.3.2 only, but should work on moodle 2.2+
==Features==
The My Stats block is a dashboard for the My Moodle and User Profile pages. It shows the student statistics about their activity and provides links to relevant areas to encourage participation.

The visibility of each statistics section can be controlled globally by an admin and per instance by the user.

Charts provide a visual representation of the data, and are available for several statistics groups. The chart data is presented as text when charts are disabled by the user or an admin

When placed in the My Moodle page, the user sees their own statistics.
When placed in the User Profile page, the user sees the statistics of the profile owner. Grade-related statistics are hidden to protect privacy in the profile page view.

This block works best in wide block regions or in the dock.
==Notes==
Charts are generated with the pChart 2.1.3 library (included)

This block makes a bunch of database queries. It has not been tested in a large production environment.