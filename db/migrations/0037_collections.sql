CREATE SEQUENCE seq_collections;
CREATE TABLE collections (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_collections'),
	rank INTEGER DEFAULT 999 NOT NULL,
	image_url VARCHAR(255),
	visible BOOLEAN NOT NULL DEFAULT 't',
	--
	created_by_user_id INT,
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT fk_collections_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
	CONSTRAINT fk_collections_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);

CREATE SEQUENCE seq_collection_cards;
CREATE TABLE collection_cards (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_collection_cards'),
	--
	collection_id INT NOT NULL,
	card_id INT NOT NULL,
	rank INT NOT NULL DEFAULT 999,
	--
	CONSTRAINT fk_collection_card_collections FOREIGN KEY (collection_id) REFERENCES collections ON DELETE CASCADE,
	CONSTRAINT fk_collection_card_cards FOREIGN KEY (card_id) REFERENCES cards ON DELETE CASCADE,
	CONSTRAINT unq_collections_cardid UNIQUE (card_id) -- a card may be at most in one collection
);
