-- tabulka product_images se zde vytvari jako dedic tabulky images -> ma totiz navic policko display_on_card
CREATE TABLE product_images (
	display_on_card BOOLEAN NOT NULL DEFAULT 't' -- zobrazovat jako dalsi fotografii na karte po fotkach z card_images
) INHERITS (images);
