-- Karta produktu
-- pri smazani budou smazany i vsechny jeji produkty
CREATE SEQUENCE seq_cards;
CREATE TABLE cards (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_cards'),
	visible BOOLEAN NOT NULL DEFAULT 't',
	deleted BOOLEAN NOT NULL DEFAULT 'f',
	has_variants BOOLEAN NOT NULL DEFAULT 'f', -- pokud je false, tak musi byt aplikacne zajisteno, ze v products bude pro tuto kartu prave 1 produkt
	brand_id INT,
	--
	created_by_user_id INT,
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT fk_cards_brands FOREIGN KEY (brand_id) REFERENCES brands,
	CONSTRAINT fk_cards_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
	CONSTRAINT fk_cards_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);

-- textual information for cards
CREATE SEQUENCE seq_card_section_types START WITH 11;
CREATE TABLE card_section_types (
	id INT PRIMARY KEY,
	code VARCHAR(255) NOT NULL,
	name VARCHAR(255),
	rank INT NOT NULL DEFAULT 999,
	--
	created_by_user_id INT,
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT unq_cardsectiontypes_code UNIQUE (code),
	CONSTRAINT fk_cardsectiontypes_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
	CONSTRAINT fk_cardsectiontypes_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);

INSERT INTO card_section_types (id,code,rank) VALUES (1,'variants',3);
INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','1','name','en','Variants');
INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','1','name','cs','Varianty');
INSERT INTO card_section_types (id,code,rank) VALUES (2,'tech_spec',2);
INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','2','name','en','Technical specification');
INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','2','name','cs','Technické specifikace');
-- Awards: reserved :)
-- INSERT INTO card_section_types (id,code,rank) VALUES (3,'awards',6);
-- INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','3','name','en','Awards');
-- INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','3','name','cs','Ocenění');
INSERT INTO card_section_types (id,code,rank) VALUES (4,'documentation',4);
INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','4','name','en','Product documentation');
INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','4','name','cs','Produktová dokumentace');
INSERT INTO card_section_types (id,code,rank) VALUES (5,'collection',5);
INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','5','name','en','Other parts of collection');
INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','5','name','cs','Další části kolekce');
INSERT INTO card_section_types (id,code,rank) VALUES (6,'info',1);
INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','6','name','en','Information');
INSERT INTO translations (table_name,record_id,key,lang,body) VALUES('card_section_types','6','name','cs','Informace');


CREATE SEQUENCE seq_card_sections;
CREATE TABLE card_sections (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_card_sections'),
	card_id INT NOT NULL,
	card_section_type_id INT NOT NULL,
	rank INT NOT NULL DEFAULT 999,
	--
	created_by_user_id INT,
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT fk_cardsections_cardsectiontypes FOREIGN KEY (card_section_type_id) REFERENCES card_section_types,
	CONSTRAINT fk_cardsections_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
	CONSTRAINT fk_cardsections_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);

-- tags 
CREATE SEQUENCE seq_card_tags;
CREATE TABLE card_tags(
	id INTEGER PRIMARY KEY DEFAULT NEXTVAL('seq_card_tags'),
	card_id INTEGER NOT NULL,
	tag_id INTEGER NOT NULL,
	rank INTEGER DEFAULT 999 NOT NULL,
	CONSTRAINT fk_card_tags_cards FOREIGN KEY (card_id) REFERENCES cards ON DELETE CASCADE,
	CONSTRAINT fk_card_tags_tags FOREIGN KEY (tag_id) REFERENCES tags ON DELETE CASCADE
);
CREATE INDEX in_cardtags_cardid ON card_tags(card_id);
CREATE INDEX in_cardtags_tagid ON card_tags(tag_id);

-- related cards
CREATE SEQUENCE seq_related_cards;
CREATE TABLE related_cards(
	id INTEGER PRIMARY KEY DEFAULT NEXTVAL('seq_related_cards'),
	card_id INTEGER NOT NULL,
	related_card_id INTEGER NOT NULL,
	rank INTEGER DEFAULT 999 NOT NULL,
	--
	CONSTRAINT fk_related_cards_cards FOREIGN KEY (card_id) REFERENCES cards ON DELETE CASCADE,
	CONSTRAINT fk_related_cards_related_cards FOREIGN KEY (related_card_id) REFERENCES cards ON DELETE CASCADE
);
CREATE INDEX in_relatedcards_cardid ON related_cards(card_id);
CREATE INDEX in_relatedcards_relatedcardid ON related_cards(related_card_id);

CREATE SEQUENCE seq_products;
CREATE TABLE products (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_products'),
	--
	-- produkt je umisten na nejake karte v nejakem poradi
	card_id INT NOT NULL,
	rank INT NOT NULL DEFAULT 999,
	--
	catalog_id VARCHAR(255) NOT NULL,
	visible BOOLEAN NOT NULL DEFAULT 't',
	deleted BOOLEAN NOT NULL DEFAULT 'f',
	--
	created_by_user_id INT,
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT unq_products_catalogid UNIQUE (catalog_id),
	CONSTRAINT fk_products_cards FOREIGN KEY (card_id) REFERENCES cards(id) ON DELETE CASCADE,
	CONSTRAINT fk_products_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
	CONSTRAINT fk_products_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);
CREATE INDEX in_products_cardid ON products (card_id);
