CREATE SEQUENCE seq_categories;
CREATE TABLE categories (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_categories'),
	--
	code VARCHAR(255), -- alternative key
	--
	parent_category_id INT,
	rank INT NOT NULL DEFAULT 999,
	visible BOOLEAN NOT NULL DEFAULT 't',
	is_filter BOOLEAN NOT NULL DEFAULT 'f',
	pointing_to_category_id INT,
	image_url VARCHAR(255),
	--
	created_by_user_id INT,
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT unq_categories_code UNIQUE (code),
	CONSTRAINT fk_categories_categories FOREIGN KEY (parent_category_id) REFERENCES categories ON DELETE CASCADE,
	CONSTRAINT fk_categories_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
	CONSTRAINT fk_categories_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users,
	CONSTRAINT fk_categories_pointingtocategories FOREIGN KEY (pointing_to_category_id) REFERENCES categories ON DELETE CASCADE
);
CREATE INDEX in_categories_parentcategoryid ON categories(parent_category_id);

-- zarazeni produktove karty v kategorii
CREATE SEQUENCE seq_category_cards;
CREATE TABLE category_cards(
	id INTEGER DEFAULT NEXTVAL('seq_category_cards') NOT NULL PRIMARY KEY,
	category_id INTEGER NOT NULL,
	card_id INTEGER NOT NULL,
	rank INTEGER DEFAULT 999 NOT NULL,
	rank_rev INTEGER DEFAULT 999 NOT NULL,
	CONSTRAINT fk_categorycards_categories FOREIGN KEY (category_id) REFERENCES categories ON DELETE CASCADE,
	CONSTRAINT fk_categorycards_cards FOREIGN KEY (card_id) REFERENCES cards ON DELETE CASCADE,
	CONSTRAINT unq_categorycards UNIQUE (category_id,card_id)
);
CREATE INDEX in_categorycards_cardid ON category_cards(card_id);
CREATE INDEX in_categorycards_categoryid_rank ON category_cards(category_id,rank);
CREATE INDEX in_categorycards_cardid_rankrev ON category_cards(card_id, rank_rev);

-- zarazeni doporucene produktove karty v kategorii
CREATE SEQUENCE seq_category_recommended_cards;
CREATE TABLE category_recommended_cards(
	id INTEGER DEFAULT NEXTVAL('seq_category_recommended_cards') NOT NULL PRIMARY KEY,
	category_id INTEGER NOT NULL,
	card_id INTEGER NOT NULL,
	rank INTEGER DEFAULT 999 NOT NULL,
	CONSTRAINT fk_categoryrecommendedcards_categories FOREIGN KEY (category_id) REFERENCES categories ON DELETE CASCADE,
	CONSTRAINT fk_categoryrecommendedcards_cards FOREIGN KEY (card_id) REFERENCES cards ON DELETE CASCADE,
	CONSTRAINT unq_categoryrecommendedcards UNIQUE (category_id,card_id)
);
CREATE INDEX in_categoryrecommendedcards_cardid ON category_recommended_cards(card_id,rank);
