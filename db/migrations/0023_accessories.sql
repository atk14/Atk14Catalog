CREATE SEQUENCE seq_accessories;
CREATE TABLE accessories(
	id INTEGER PRIMARY KEY DEFAULT NEXTVAL('seq_accessories'),
	card_id INTEGER NOT NULL,
	accessory_id INTEGER NOT NULL,
	rank INTEGER DEFAULT 999 NOT NULL,
	--
	CONSTRAINT fk_accessories_cards FOREIGN KEY (card_id) REFERENCES cards ON DELETE CASCADE,
	CONSTRAINT fk_accessories_accessory_cards FOREIGN KEY (accessory_id) REFERENCES cards ON DELETE CASCADE
);
CREATE INDEX in_accessories_cardid ON accessories(card_id);
CREATE INDEX in_accessories_accessoryid ON accessories(accessory_id);
