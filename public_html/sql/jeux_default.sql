-- Initialise la DB avec des données par défaut

INSERT INTO accounts (account_name, account_passwd)
VALUES ('test', 'test')
ON DUPLICATE KEY UPDATE account_id = LAST_INSERT_ID(account_id);

SET @user_id = LAST_INSERT_ID();

INSERT INTO jeux (nom, description, genre, plateforme, image, id_utilisateur)
VALUES
  ('Zelda', 'Adventure game', 'Adventure', 'Switch', 'zelda.jpg', @user_id),
  ('Minecraft', 'Sandbox building game', 'Sandbox', 'PC', 'minecraft.jpeg', @user_id),
  ('Among Us', 'Multiplayer deduction game', 'Party', 'Mobile', 'amongus.jpg', @user_id),
  ('Super Mario Odyssey', '3D platformer', 'Platformer', 'Switch', 'mario.jpg', @user_id),
  ('Stardew Valley', 'Farming simulation', 'Simulation', 'PC', 'stardew.jpeg', @user_id),
  ('Overwatch', 'Team shooter', 'FPS', 'PC', 'overwatch.jpg', @user_id),
  ('Fortnite', 'Battle royale', 'Battle Royale', 'PC', 'fortnite.jpeg', @user_id),
  ('Celeste', 'Challenging platformer', 'Platformer', 'Switch', 'celeste.jpeg', @user_id),
  ('Animal Crossing', 'Life simulation', 'Simulation', 'Switch', 'animalcrossing.jpg', @user_id),
  ('Hades', 'Action roguelike', 'Action', 'Switch', 'hades.jpg', @user_id),
  ('Apex Legends', 'Hero shooter battle royale', 'Battle Royale', 'PC', 'apexlegends.jpeg', @user_id),
  ('Halo Infinite', 'Sci-fi shooter', 'FPS', 'Xbox', 'haloinfinite.jpeg', @user_id),
  ('The Witcher 3', 'Fantasy RPG', 'RPG', 'PC', 'witcherIII.jpg', @user_id),
  ('Portal 2', 'Puzzle platformer', 'Puzzle', 'PC', 'portal2.jpeg', @user_id),
  ('Cyberpunk 2077', 'Open world RPG', 'RPG', 'PC', 'cyberpunk.jpg', @user_id);