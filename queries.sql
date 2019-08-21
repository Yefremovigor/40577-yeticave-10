INSERT INTO categories (name, alias) VALUES
  ('Доски и лыжи', 'boards'),
  ('Крепления', 'attachment'),
  ('Ботинки', 'boots'),
  ('Одежда', 'clothing'),
  ('Инструменты', 'tools'),
  ('Разное', 'other');

INSERT INTO users (email, name, password, contacts) VALUES
  ('white@olympicchampion2018.org', 'Шон Уайт', 'e54f9a690f65839504252f77f19899ff', 'facebook.com/ShaunWhite'),
  ('hirano@olympicchampion2018.org', 'Хирано', 'd83a5801d7bf75f2dfee5ac1e50bdf12', 'facebook.com/ayumuhirano'),
  ('james@olympicchampion2018.org', 'Скотти', 'f1d712802d53d73f63f5601ebc7ace3b', 'facebook.com/scottyjamesofficial');

INSERT INTO lots (title, description, img, start_price, finish_date, bet_step, author_id, category_id) VALUES
  ('2014 Rossignol District Snowboard', 'Доска просто бомба!', 'img/lot-1.jpg', '10999', '2019-08-25', '100', '1', '1'),
  ('DC Ply Mens 2016/2017 Snowboard', 'Верный друг', 'img/lot-2.jpg', '159999', '2019-08-24', '1', '2', '1'),
  ('Крепления Union Contact Pro 2015 года размер L/XL', 'Держат крепчи чем ипотека сами знаете что...', 'img/lot-3.jpg', '8000', '2019-08-26', '10', '3', '2'),
  ('Ботинки для сноуборда DC Mutiny Charocal', 'Потом почти не пахнут', 'img/lot-4.jpg', '10999', '2019-08-24', '10', '1', '3'),
  ('Куртка для сноуборда DC Mutiny Charocal', 'Тепло на трассе, стильно в шале', 'img/lot-5.jpg', '7500', '2019-08-27', '500', '2', '4'),
  ('Маска Oakley Canopy', 'Почти как новые', 'img/lot-6.jpg', '5400', '2019-08-28', '100', '3', '6');

INSERT INTO bets (bet, user_id, lot_id)  VALUES
  ('5400','1','6');
INSERT INTO bets (bet, user_id, lot_id)  VALUES
  ('5500','2','6');
INSERT INTO bets (bet, user_id, lot_id)  VALUES
  ('5600','1','6');
