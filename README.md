# Advanced Contact Form (WordPress Plugin)

A simple but powerful WordPress Contact Form Plugin with:

✅ Beautiful blue design (frontend form)

✅ Stores messages directly into WordPress Database

✅ Admin Dashboard Page to view all messages

✅ Sends email notifications to site admin

# Features

Easy to install and use with a shortcode [advanced_contact_form]

Responsive blue-styled form with Name, Email, Message fields

Stores submissions into a custom WordPress database table

Automatic email alert to WordPress admin email

Admin Dashboard → Contact Messages page for viewing submissions

Lightweight and beginner-friendly

# Installation

Download or clone this repository:

git clone https://github.com/your-username/advanced-contact-form.git


Upload the folder advanced-contact-form into your WordPress wp-content/plugins/ directory.

Activate the plugin from WordPress Admin Dashboard → Plugins.

Create or edit a Page/Post and add the shortcode:

[advanced_contact_form]


# How It Works

When a visitor submits the form → their info (name, email, message) gets:

Saved into the database table wp_acf_messages.

Sent to the site admin email (set in WP Settings → General).

Admin can view all saved messages inside WordPress Dashboard → Contact Messages.

# Development Notes

Database table is auto-created when plugin is activated.

Uses WordPress dbDelta() for database migration.

Uses sanitize_text_field, sanitize_email, sanitize_textarea_field for security.

Styled with custom CSS (frontend + admin panel).

# License

This plugin is released under the GPLv2 or later
 license.

You are free to use, modify, and distribute it under the same license.

# Credits

Developed by Abir Ovi.
Plugin UI & structure inspired by modern WordPress plugin practices.
