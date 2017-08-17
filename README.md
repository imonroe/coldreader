Coldreader

README:

Preparing to update.

*** IMPORTANT NOTE: This is ALPHA code.  Please, please don't use it on production systems. I'm publishing it on Github as a code sample and for version control, not as a finished application.  I can't be responsible for evils that befall you, should you ignore this warning. ***

Coldreader is designed to be a personal content management system.  It is particularly well-suited to users who:
	* Want to keep track of many different kinds of things
	* Want a generalized database that can be applied to many kinds of problems simultaneously
	* Run their own server (developed for LAMP environments, may work in others.)
	* Use Google/Gmail/Google Apps for stuff like email, contacts, and calendar.
	* Want to own and control their own data (I'm looking at you, Evernote/Facebook/Google.)
	* Are comfortable with coding in PHP and Javascript (the most powerful uses for this software require writing some code yourself.)
	* Are comfortable with plugging in your own APIs and datasources, per your particular situation.

Coldreader is NOT designed to:
	* Use as a CMS for a public web site. It's a system designed to be used by one person or a small team to keep track of private data
	* Be optimized for SEO.  In fact, it's designed to keep as low a profile for bots as possible.
	* Be fully functional out of the box.

People have all kinds of content that they want to keep track of -- a calendar, a todo list, a contact list, a project-management solution, and a huge variety of information they need to track day to day, or in a database.

Furthermore -- much of this content is related, and is managed in similar ways.  Most of the time, the relations are secondary, an afterthought.  But in this case, what this tool does is operate as a generalized database for keeping track of ALL your personal knowledge, regardless of where it lives on the internet.

Some tools exist to do this -- evernote, for instance.  but it’s bulky and complicated and expensive, and the data live on their servers.

Coldreader has a generalized database structure where there are two essential elements -- Subjects, and Aspects.  A subject is, roughly speaking, a noun.  A subject can have any number of aspects, which represent knowable facts about a subject.  These can be binary or text of any format (I find markdown and JSON particularly helpful, for instance).  Aspects come in a variety of flavors, each of which may have a custom display method.  So, for instance, many kinds of Subjects may have a Street Address aspect, but it’s always displayed in the same way.  Subjects may have arbitrary relationships between one another.  Each flavor of aspect can be customized to display in any way you like, and, critically, every flavor of aspect has a parse() function, by which it can query additional data sources, create new aspects, and append them automatically to the subject to which it belongs.  This means your database can, to a degree, populate itself and keep itself updated as the information available changes.

And a key feature is that because YOU run the software, YOU own the data.  Data mine your friends like facebook, but without all the evil!  Use it as a search front-end, so you don’t build up Google’s search history about you.  Use it as a project management tool, customer relationship management, todo list, research tool, as a place to build up your recipe library, keep files on any subject, manage your database of DVDs, whatever you care about. If you want to keep track of it, Coldreader (and a little ingenuity) can do it.

AUTHOR: Ian Monroe (ian@ianmonroe.com)

CHANGELOG:  Initial commit.  Probably won't work for you yet.  No installer, no tests, bare bones.

NEWS:  Initial commit.

INSTALL:  Well, to get the thing going in it's most basic form, you can copy the files to your server, create a DB based on the database_schema.txt document, and then create a config file by copying /src/config_SAMPLE.PHP to /src/config.php, and updating it with your database information.

To actually USE it, you'll need some API keys.  You'll need to set up an app in the Google Developer's console, with permissions for, at minimum: Drive, Calendar, Contacts, Tasks, userinfo.email, userinfo.profile.

You'll also probably want a FullContact API key, an Aylien API key, and a Mashshape key as well.

Getting these set up is beyond the scope here at the moment, but the necessary information can be stored in the /src/config.php file once you have it.

COPYING/LICENSE:  There's plenty of third-party libs in use, and nothing here should be interpreted to change or contradict anything that is stipulated in the licenses for those components.  As for my code, it's Creative Commons Attribution-NonCommercial-ShareAlike 3.0 United States. (http://creativecommons.org/licenses/by-nc-sa/3.0/us/).  For more information, contact Ian Monroe: ian@ianmonroe.com
 

BUGS:  Too numerous to mention, undoubtedly.  Some parts of this software are designed to automatically add new executable source code to itself as you use it, and thus might be potentially dangerous in the wrong hands.  *This is NOT a production-ready version*.  Install it only if you are suitably well-versed in server setup, DB admin, and web programming to know what you're doing.  At this time, I am not providing any support whatsoever.

WARRANTY: No warranty whatsoever is expressed or implied.  Use this software at your own risk.

