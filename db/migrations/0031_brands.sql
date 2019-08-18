CREATE SEQUENCE seq_brands;
CREATE TABLE brands (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_brands'),
	rank INTEGER DEFAULT 999 NOT NULL,
	name VARCHAR(255),
	url VARCHAR(255),
	logo_url VARCHAR(255),
	visible BOOLEAN NOT NULL DEFAULT 't',
	--
	created_by_user_id INT,
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT fk_brands_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
	CONSTRAINT fk_brands_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);
