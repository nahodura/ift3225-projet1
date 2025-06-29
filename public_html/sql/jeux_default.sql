-- Initialise la DB avec des données par défaut

INSERT INTO accounts (account_name, account_passwd)
VALUES ('test', 'test')
ON DUPLICATE KEY UPDATE account_id = LAST_INSERT_ID(account_id);

SET @user_id = LAST_INSERT_ID();

INSERT INTO jeux (nom, description, genre, plateforme, image, id_utilisateur)
VALUES
  ('Zelda', 'Adventure game', 'Adventure', 'Switch', 'zelda.jpg', @user_id),
  ('Minecraft', 'Sandbox building game', 'Sandbox', 'PC', 'minecraft.jpg', @user_id),
  ('Among Us', 'Multiplayer deduction game', 'Party', 'Mobile', 'amongus.jpg', @user_id),
  ('Super Mario Odyssey', '3D platformer', 'Platformer', 'Switch', 'mario.jpg', @user_id),
  ('Stardew Valley', 'Farming simulation', 'Simulation', 'PC', 'stardew.jpg', @user_id),
  ('Overwatch', 'Team shooter', 'FPS', 'PC', 'overwatch.jpg', @user_id),
  ('Fortnite', 'Battle royale', 'Battle Royale', 'PC', 'fortnite.jpg', @user_id),
  ('Celeste', 'Challenging platformer', 'Platformer', 'Switch', 'celeste.jpg', @user_id),
  ('Animal Crossing', 'Life simulation', 'Simulation', 'Switch', 'acnh.jpg', @user_id),
  ('Hades', 'Action roguelike', 'Action', 'Switch', 'hades.jpg', @user_id),
  ('Apex Legends', 'Hero shooter battle royale', 'Battle Royale', 'PC', 'apex.jpg', @user_id),
  ('Halo Infinite', 'Sci-fi shooter', 'FPS', 'Xbox', 'haloinfinite.jpg', @user_id),
  ('The Witcher 3', 'Fantasy RPG', 'RPG', 'PC', 'witcher3.jpg', @user_id),
  ('Portal 2', 'Puzzle platformer', 'Puzzle', 'PC', 'portal2.jpg', @user_id),
  ('Cyberpunk 2077', 'Open world RPG', 'RPG', 'PC', 'cyberpunk.jpg', @user_id);