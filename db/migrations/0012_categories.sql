CREATE SEQUENCE seq_categories;
CREATE TABLE categories (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_categories'),
	parent_category_id INT,
	rank INT NOT NULL DEFAULT 999,
	visible BOOLEAN NOT NULL DEFAULT 't',
	is_filter BOOLEAN NOT NULL DEFAULT 'f',
	image_url VARCHAR(255),
	--
	created_by_user_id INT,
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT fk_categories_categories FOREIGN KEY (parent_category_id) REFERENCES categories ON DELETE CASCADE,
	CONSTRAINT fk_categories_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
	CONSTRAINT fk_categories_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);
