<?php
/**
 * Plugin Name: Advanced Contact Form
 * Plugin URI:  https://github.com/AbirOvi1/Wordpress-Plugin-Dev-Advanced-Contact-Form
 * Description: Contact Form with Blue UI + Stores messages in DB + Admin Dashboard.
 * Version:     1.0
 * Author:      Abir Ovi
 * Author URI:  https://github.com/AbirOvi1
 * License:     GPL2
 */

if (!defined('ABSPATH')) exit;

// Enqueue Frontend Styles
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('acf-styles', plugin_dir_url(__FILE__) . 'style.css');
});

// Enqueue Admin Styles
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('acf-admin-styles', plugin_dir_url(__FILE__) . 'admin.css');
});

// Create Table on Activation
register_activation_hook(__FILE__, function () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'acf_messages';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        message text NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});

// Shortcode for Form
add_shortcode('advanced_contact_form', function () {
    ob_start(); ?>
    <form method="post" class="acf-form">
        <h2>Contact Us</h2>
        <label for="acf_name">Your Name</label>
        <input type="text" name="acf_name" required>

        <label for="acf_email">Your Email</label>
        <input type="email" name="acf_email" required>

        <label for="acf_message">Message</label>
        <textarea name="acf_message" rows="5" required></textarea>

        <button type="submit" name="acf_submit">Send Message</button>
    </form>
    <?php
    return ob_get_clean();
});

// Handle Form Submission
add_action('init', function () {
    if (isset($_POST['acf_submit'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'acf_messages';

        $name    = sanitize_text_field($_POST['acf_name']);
        $email   = sanitize_email($_POST['acf_email']);
        $message = sanitize_textarea_field($_POST['acf_message']);

        // Save to DB
        $wpdb->insert($table_name, [
            'name'    => $name,
            'email'   => $email,
            'message' => $message,
        ]);

        // Send Email
        $admin_email = get_option('admin_email');
        $subject = "New Contact Form Submission from $name";
        $body = "Name: $name\nEmail: $email\nMessage:\n$message";
        wp_mail($admin_email, $subject, $body);

        add_action('wp_footer', function () {
            echo "<script>alert('✅ Thank you! Your message has been sent.');</script>";
        });
    }
});

// Add Admin Menu
add_action('admin_menu', function () {
    add_menu_page(
        'Contact Messages',
        'Contact Messages',
        'manage_options',
        'acf-messages',
        'acf_render_admin_page',
        'dashicons-email-alt',
        20
    );
});

// Render Admin Page
function acf_render_admin_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'acf_messages';
    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");

    echo "<div class='wrap'><h1>Contact Messages</h1>";
    if ($results) {
        echo "<table class='acf-table'>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Date</th></tr>";
        foreach ($results as $row) {
            echo "<tr>
                    <td>{$row->id}</td>
                    <td>{$row->name}</td>
                    <td>{$row->email}</td>
                    <td>{$row->message}</td>
                    <td>{$row->created_at}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No messages yet.</p>";
    }
    echo "</div>";
}