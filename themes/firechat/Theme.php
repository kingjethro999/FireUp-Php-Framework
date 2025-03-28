<?php

namespace Themes\FireChat;

use FireUp\Theming\AbstractTheme;

class Theme extends AbstractTheme
{
    /**
     * Get the theme name.
     *
     * @return string
     */
    public function getName()
    {
        return 'firechat';
    }

    /**
     * Get the theme description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'A real-time chat application with one-on-one and group chat capabilities.';
    }

    /**
     * Get the theme version.
     *
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * Get the theme author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return 'FireUp Team';
    }

    /**
     * Get the theme's parent theme name.
     *
     * @return string|null
     */
    public function getParent()
    {
        return 'default';
    }

    /**
     * Install the theme and create necessary database tables.
     *
     * @return void
     */
    public function install()
    {
        $db = $this->app->getDatabase();
        
        // Create users table
        $db->query("CREATE TABLE IF NOT EXISTS firechat_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            avatar VARCHAR(255) DEFAULT NULL,
            status ENUM('online', 'offline', 'away') DEFAULT 'offline',
            last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");

        // Create chat_rooms table
        $db->query("CREATE TABLE IF NOT EXISTS firechat_rooms (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            is_private BOOLEAN DEFAULT FALSE,
            created_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES firechat_users(id)
        )");

        // Create room_members table
        $db->query("CREATE TABLE IF NOT EXISTS firechat_room_members (
            room_id INT,
            user_id INT,
            role ENUM('admin', 'member') DEFAULT 'member',
            joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (room_id, user_id),
            FOREIGN KEY (room_id) REFERENCES firechat_rooms(id),
            FOREIGN KEY (user_id) REFERENCES firechat_users(id)
        )");

        // Create messages table
        $db->query("CREATE TABLE IF NOT EXISTS firechat_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            room_id INT,
            sender_id INT,
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (room_id) REFERENCES firechat_rooms(id),
            FOREIGN KEY (sender_id) REFERENCES firechat_users(id)
        )");

        // Create message_reads table
        $db->query("CREATE TABLE IF NOT EXISTS firechat_message_reads (
            message_id INT,
            user_id INT,
            read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (message_id, user_id),
            FOREIGN KEY (message_id) REFERENCES firechat_messages(id),
            FOREIGN KEY (user_id) REFERENCES firechat_users(id)
        )");
    }
} 