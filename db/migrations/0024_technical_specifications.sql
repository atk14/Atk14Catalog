CREATE SEQUENCE seq_technical_specification_keys;
CREATE TABLE technical_specification_keys (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_technical_specification_keys'),
	--
	code VARCHAR(255), -- alternative key
	--
	key VARCHAR(255) NOT NULL,
	--
	created_by_user_id INT,
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT unq_technicalspecificationkeys_code UNIQUE (code),
	CONSTRAINT unq_technicalspecificationkeys_key UNIQUE (key),
	CONSTRAINT fk_technicalspecificationkeys_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
	CONSTRAINT fk_technicalspecificationkeys_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);

CREATE SEQUENCE seq_technical_specifications;
CREATE TABLE technical_specifications (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_technical_specifications'),
	card_id INT NOT NULL,
	technical_specification_key_id INT NOT NULL,
	content TEXT,
	rank INT DEFAULT 999 NOT NULL,
	--
	created_by_user_id INT,
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT unq_technicalspecifications UNIQUE (card_id,technical_specification_key_id),
	CONSTRAINT fk_technicalspecifications_cards FOREIGN KEY (card_id) REFERENCES cards ON DELETE CASCADE,
	CONSTRAINT fk_technicalspecifications_keys FOREIGN KEY (technical_specification_key_id) REFERENCES technical_specification_keys ON DELETE CASCADE,
	CONSTRAINT fk_technicalspecifications_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
	CONSTRAINT fk_technicalspecifications_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);
