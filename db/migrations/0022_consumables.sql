CREATE SEQUENCE seq_consumables;
CREATE TABLE consumables(
	id INTEGER PRIMARY KEY DEFAULT NEXTVAL('seq_consumables'),
	card_id INTEGER NOT NULL,
	consumable_id INTEGER NOT NULL,
	rank INTEGER DEFAULT 999 NOT NULL,
	--
	CONSTRAINT fk_consumables_cards FOREIGN KEY (card_id) REFERENCES cards ON DELETE CASCADE,
	CONSTRAINT fk_consumables_consumable_cards FOREIGN KEY (consumable_id) REFERENCES cards ON DELETE CASCADE
);
CREATE INDEX in_consumables_cardid ON consumables(card_id);
CREATE INDEX in_consumables_consumableid ON consumables(consumable_id);
