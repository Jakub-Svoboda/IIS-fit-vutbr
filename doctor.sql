CREATE TABLE IF NOT EXISTS pacient (
  id_pacient INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rodne_cislo BIGINT,
  jmeno VARCHAR(50) NOT NULL,
  prijmeni VARCHAR(50) NOT NULL,
  pohlavi VARCHAR(10),
  adresa VARCHAR(100),
  id_pojistovna INT
);

CREATE TABLE IF NOT EXISTS lecba(
	id_lecba INT PRIMARY KEY AUTO_INCREMENT,
	pocet_dni_uzivani INT,
	mnozstvi INT,
	id_lek INT NOT NULL,
	id_pacient INT  NOT NULL
);

CREATE TABLE IF NOT EXISTS lek(
	id_lek INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nazev VARCHAR(50) NOT NULL,
  popis VARCHAR(1024),
	predpis BOOLEAN
);

CREATE TABLE IF NOT EXISTS pojistovna(
	id_pojistovna INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	jmeno VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS osetreni(
	id_osetreni INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	datum DATE,
	popis VARCHAR(255),
  id_pacient INT NOT NULL
);

CREATE TABLE IF NOT EXISTS faktura(
	id_faktura INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	datum VARCHAR(10) ,		/*DD.MM.YYYY*/
	castka INT NOT NULL,
  id_pojistovna INT,
  id_pacient INT NOT NULL
);

CREATE TABLE IF NOT EXISTS navsteva(
	id_navsteva INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	datum VARCHAR(10),		/*DD.MM.YYYY*/
	popis VARCHAR(255),
  id_pacient INT NOT NULL
);

CREATE TABLE IF NOT EXISTS vykon(
	nazev VARCHAR(50) NOT NULL PRIMARY KEY,
	popis VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS vysetreni(
	id_vysetreni INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  nazev VARCHAR(50) NOT NULL,
  id_pacient INT NOT NULL,
  FOREIGN KEY (id_pacient) REFERENCES pacient(id_pacient),
  FOREIGN KEY (nazev) REFERENCES vykon(nazev)
);

CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` char(60) NOT NULL,
  `username` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

ALTER TABLE pacient ADD CONSTRAINT fk_pojistovna FOREIGN KEY (id_pojistovna) REFERENCES pojistovna(id_pojistovna);
ALTER TABLE lecba ADD CONSTRAINT fk_lek FOREIGN KEY (id_lek) REFERENCES lek(id_lek);
ALTER TABLE lecba ADD CONSTRAINT fk_pacient FOREIGN KEY (id_pacient) REFERENCES pacient(id_pacient);
ALTER TABLE osetreni ADD CONSTRAINT fk_pacient2 FOREIGN KEY (id_pacient) REFERENCES pacient(id_pacient);
ALTER TABLE faktura ADD CONSTRAINT fk_pacient3 FOREIGN KEY (id_pacient) REFERENCES pacient(id_pacient);
ALTER TABLE navsteva ADD CONSTRAINT fk_pacient4 FOREIGN KEY (id_pacient) REFERENCES pacient(id_pacient);

INSERT INTO lek( nazev, predpis, popis) VALUES ('valium', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');
INSERT INTO lek( nazev, predpis, popis) VALUES ('aspirin', 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');
INSERT INTO lek( nazev, predpis, popis) VALUES ('acylpyrin', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');
INSERT INTO lek( nazev, predpis, popis) VALUES ('morfium', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');
INSERT INTO lek( nazev, predpis, popis) VALUES ('paracetamol', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');
INSERT INTO lek( nazev, predpis, popis) VALUES ('gabagama', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');
INSERT INTO lek( nazev, predpis, popis) VALUES ('ibuprofen', 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');
INSERT INTO lek( nazev, predpis, popis) VALUES ('vikodin', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');
INSERT INTO lek( nazev, predpis, popis) VALUES ('m-iodbenzylguanidin', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');
INSERT INTO lek( nazev, predpis, popis) VALUES ('panadol', 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');
INSERT INTO lek( nazev, predpis, popis) VALUES ('kabiven', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');
INSERT INTO lek( nazev, predpis, popis) VALUES ('Cabometyx', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ultricies mi. Donec placerat nisi in diam scelerisque, eget elementum mi aliquet.');

INSERT INTO pojistovna(jmeno) VALUES ('Pojišťovna ministerstva vnitra');
INSERT INTO pojistovna(jmeno) VALUES ('Revírní Bratrská Pokladna');
INSERT INTO pojistovna(jmeno) VALUES ('Vojenská Zdravotní pojišťovna');
INSERT INTO pojistovna(jmeno) VALUES ('Všeobecná Zdravotní pojišťovna');
INSERT INTO pojistovna(jmeno) VALUES ('Zaměstnanecká pojišťovna Škoda');

INSERT INTO pacient(rodne_cislo, jmeno, prijmeni, pohlavi, adresa, id_pojistovna) VALUES (9407035517, 'Jan', 'Nemocný', 'muž','Hlavní 10',1);
INSERT INTO pacient(rodne_cislo,jmeno, prijmeni, pohlavi, adresa, id_pojistovna) VALUES (9158065147,'Jana', 'Nemocná', 'žena','Hlavní 10',1);
INSERT INTO pacient(rodne_cislo, jmeno, prijmeni, pohlavi, adresa, id_pojistovna) VALUES (8203142178,'Láďa', 'Hruška', 'muž','Chutná 12',3);
INSERT INTO pacient(rodne_cislo, jmeno, prijmeni, pohlavi, adresa, id_pojistovna) VALUES (7612195272, 'Jirka', 'Babica', 'muž','Záporná 8',3);
INSERT INTO pacient(rodne_cislo, jmeno, prijmeni, pohlavi, adresa, id_pojistovna) VALUES (6701293577, 'Jack', 'ONeill', 'muž','Hlavní 30',1);
INSERT INTO pacient(rodne_cislo, jmeno, prijmeni, pohlavi, adresa, id_pojistovna) VALUES (9511131894,'Jack', 'Sparrow', 'muž','Lodní 1',3);
INSERT INTO pacient(rodne_cislo, jmeno, prijmeni, pohlavi, adresa, id_pojistovna) VALUES (8561126585,'Keira', 'Knightley', 'žena','Brněnská 420',3);
INSERT INTO pacient(rodne_cislo, jmeno, prijmeni, pohlavi, adresa, id_pojistovna) VALUES (690814564,'Dan', 'Nekonečný', 'muž','Ohnivá 15',2);

INSERT INTO faktura( datum, castka, id_pojistovna, id_pacient) VALUES ( '2012-07-03', 422, 1,3);
INSERT INTO faktura( datum, castka, id_pojistovna, id_pacient) VALUES ( '2017-07-25', 67, 1,5);
INSERT INTO faktura( datum, castka, id_pojistovna, id_pacient) VALUES ( '2016-12-21', 666, 2,8);
INSERT INTO faktura( datum, castka, id_pojistovna, id_pacient) VALUES ( '2012-07-03', 42, 1,4);
INSERT INTO faktura( datum, castka, id_pojistovna, id_pacient) VALUES ( '2014-11-07', 302, 1,5);
INSERT INTO faktura( datum, castka, id_pojistovna, id_pacient) VALUES ( '2015-07-26', 3000, 1,5);
INSERT INTO faktura( datum, castka, id_pojistovna, id_pacient) VALUES ( '2016-07-26', 100, 1,5);
INSERT INTO faktura( datum, castka, id_pojistovna, id_pacient) VALUES ( '2010-05-19', 202, 1,5);

INSERT INTO lecba( pocet_dni_uzivani, mnozstvi, id_lek, id_pacient) VALUES (7, 3, 1, 3);
INSERT INTO lecba( pocet_dni_uzivani, mnozstvi, id_lek, id_pacient) VALUES (21, 6, 4, 5);
INSERT INTO lecba( pocet_dni_uzivani, mnozstvi, id_lek, id_pacient) VALUES (28, 1, 5, 8);
INSERT INTO lecba( pocet_dni_uzivani, mnozstvi, id_lek, id_pacient) VALUES (21, 2, 1, 4);
INSERT INTO lecba( pocet_dni_uzivani, mnozstvi, id_lek, id_pacient) VALUES (25, 5, 1, 1);
INSERT INTO lecba( pocet_dni_uzivani, mnozstvi, id_lek, id_pacient) VALUES (20, 2, 2, 1);
INSERT INTO lecba( pocet_dni_uzivani, mnozstvi, id_lek, id_pacient) VALUES (15, 3, 3, 1);

INSERT INTO navsteva(datum, popis, id_pacient) VALUES ('2012-09-03', 'Podezření na horečku, změřená teplota',3);
INSERT INTO navsteva(datum, popis, id_pacient) VALUES ('2015-02-03', 'Nevolnost, změřen tlak',5);
INSERT INTO navsteva(datum, popis, id_pacient) VALUES ('2013-07-21', 'Preventivní kontrola',8);
INSERT INTO navsteva(datum, popis, id_pacient) VALUES ('2016-12-07', 'Očkování',4);

INSERT INTO osetreni(datum, popis, id_pacient) VALUES ('2012-09-03', 'Změřená teplota', 3);
INSERT INTO osetreni(datum, popis, id_pacient) VALUES ('2015-02-03', 'Změřený tlak', 5);
INSERT INTO osetreni(datum, popis, id_pacient) VALUES ('2013-07-21', 'Kontrola zraku', 8);
INSERT INTO osetreni(datum, popis, id_pacient) VALUES ('2016-12-07', 'Očkování proti tetanu', 4);
INSERT INTO osetreni(datum, popis, id_pacient) VALUES ('2016-07-12', 'Očkování proti žloutence', 4);
INSERT INTO osetreni(datum, popis, id_pacient) VALUES ('2016-07-15', 'Očkování proti žloutence', 1);
INSERT INTO osetreni(datum, popis, id_pacient) VALUES ('2017-10-25', 'Kontrola zraku', 1);

INSERT INTO vykon(nazev, popis) VALUES ('Rentgen', 'pacientovi proveden rentgen části těla. Výsledky jsou odeslány zpět do ordinace');
INSERT INTO vykon(nazev, popis) VALUES ('Magnetická rezonance', 'pacientovi provedena MRI a výsledky jsou odeslány do ordinace');
INSERT INTO vykon(nazev, popis) VALUES ('Změření zraku', 'Pacientovi je změřen zrak a případně předepsány brýle');
INSERT INTO vykon(nazev, popis) VALUES ('Sádra', 'Pacientovi je zasádrována zlomená ruka');
INSERT INTO vykon(nazev, popis) VALUES ('Operace slepého střeva', 'Pacientovi je vyoperováno slepé střevo');

INSERT INTO vysetreni(nazev, id_pacient) VALUES ('Rentgen', 2);
INSERT INTO vysetreni(nazev, id_pacient) VALUES ('Změření zraku', 3);
INSERT INTO vysetreni(nazev, id_pacient) VALUES ('Magnetická rezonance', 4);
INSERT INTO vysetreni(nazev, id_pacient) VALUES ('Rentgen', 1);
INSERT INTO vysetreni(nazev, id_pacient) VALUES ('Magnetická rezonance', 1);

INSERT INTO users(password,username,role) VALUES ('$2b$10$Cn.y7xWpxKHt1JhczcMKYemREeLcH5etAfP13R8.Cx9nB4CqlicHq','admin','admin');
INSERT INTO users(password,username,role) VALUES ('$2b$10$/UmT/UDWNuyC38q3NMHNmOfChSx4EFMdP9PUrVAaEdk6RpF8d/0yC','doctor','doctor');
INSERT INTO users(password,username,role) VALUES ('$2b$10$BWJEkcmdl3hh2My7Ycl6Uuy05qb3yBC1NFMYQwdgyeqeGJTd0tMPa','sestra','sestra');


DELIMITER $$
CREATE TRIGGER pacient_insert BEFORE INSERT ON pacient
FOR EACH ROW
  BEGIN
    IF  NEW.rodne_cislo NOT REGEXP '[0-9][0-9](0|1|2|3|5|6|7|8)[0-9][0-3][0-9][0-9]{3,4}' 
    THEN SIGNAL SQLSTATE '20000'
    SET MESSAGE_TEXT = 'Wrong data in column "rodne_cislo"';
    END IF;
  END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER pacient_update BEFORE UPDATE ON pacient
FOR EACH ROW
  BEGIN
    IF NEW.rodne_cislo NOT REGEXP '[0-9][0-9](0|1|2|3|5|6|7|8)[0-9][0-3][0-9][0-9]{3,4}'
    THEN SIGNAL SQLSTATE '20000'
    SET MESSAGE_TEXT = 'Wrong data in column "rodne_cislo"';
    END IF;
  END $$
DELIMITER ;