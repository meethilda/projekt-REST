# REST-webbtjänst
Detta repo innehåller filer för att skapa ett API som representerar information i ett CV. Att hämta information från detta API är möjligt för alla. Men POST, PUT och DELETE omsluts av en kontroll av cookie, vilket endast gör det möjligt att hantera detta från administrations-gränssnittet.

## Skapa databas
CREATE TABLE IF NOT EXISTS User (
	Uid int(11) NOT NULL AUTO_INCREMENT,
	Uemail varchar(152) NOT NULL,
	Udesc text NULL DEFAULT NULL,
	UfirstName varchar(52) NOT NULL,
	UlastName varchar(52) NOT NULL,
	Upassword varchar(52) NOT NULL,
	facebook varchar(152) NULL DEFAULT NULL,
	instagram varchar(152) NULL DEFAULT NULL,
	linkedin varchar(152) NULL DEFAULT NULL,
	PRIMARY KEY (Uid, Uemail)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS Studies(
	Sid INT(11) NOT NULL AUTO_INCREMENT,
	Sname VARCHAR(152) NOT NULL,
	Scity VARCHAR(152) NOT NULL,
	SstartDate DATE NOT NULL,
	SendDate DATE NOT NULL,
	UserID INT(11) NOT NULL,
	PRIMARY KEY(Sid)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS Work (
	Wid int(11) NOT NULL AUTO_INCREMENT,
	Wname varchar(152) NOT NULL,
	Wtitle varchar(152) NOT NULL,
	WstartDate DATE NOT NULL,
	WendDate DATE NOT NULL,
	Wdesc text NULL DEFAULT NULL,
	UserID int(11) NOT NULL,
	PRIMARY KEY (Wid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS Portfolio (
	Pid int(11) NOT NULL AUTO_INCREMENT,
	Ptitle varchar(152) NOT NULL,
	Purl text NOT NULL,
	Pdesc text NULL DEFAULT NULL,
	Pcreated DATE NOT NULL,
	UserID int(11) NOT NULL,
	PRIMARY KEY (Pid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `Studies` ADD CONSTRAINT `study_fk` FOREIGN KEY (`UserID`) REFERENCES `User`(`Uid`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `Work` ADD CONSTRAINT `work_fk` FOREIGN KEY (`UserID`) REFERENCES `User`(`Uid`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `Portfolio` ADD CONSTRAINT `portfolio_fk` FOREIGN KEY (`UserID`) REFERENCES `User`(`Uid`) ON DELETE CASCADE ON UPDATE CASCADE;
