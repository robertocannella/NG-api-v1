INSERT INTO user
    (name,username,password_hash,api_key) VALUES
        ('roberto','roberto','asdfor','api123');


INSERT INTO task
    (name, priority, is_completed, user_id) VALUES
        ('Buy new shoes', 1, true,1),
        ('Renew passport', 2, false,1),
        ('Paint wall', NULL, true,1);

UPDATE task SET user_id = 1;