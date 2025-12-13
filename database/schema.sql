

CREATE TABLE contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id bigint(20) unsigned NOT NULL,
  contact_id bigint(20) unsigned NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (contact_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_contact (user_id, contact_id)
);