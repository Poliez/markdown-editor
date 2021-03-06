DROP SCHEMA IF EXISTS markdown;

CREATE SCHEMA markdown;

USE markdown;

CREATE TABLE User(
    Id INT PRIMARY KEY AUTO_INCREMENT,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Name VARCHAR(70) NOT NULL,
    Surname VARCHAR(70) NOT NULL,
    Username VARCHAR(20) NOT NULL UNIQUE,
    PasswordHash VARCHAR(60) NOT NULL,
    RegistrationDate DATETIME NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Folder(
    Id INT PRIMARY KEY AUTO_INCREMENT,
    IdUser INT NOT NULL,
    Name VARCHAR(255) NOT NULL,
    Deleted BIT NOT NULL DEFAULT 0,

    FOREIGN KEY (IdUser) REFERENCES User(Id),
    UNIQUE(IdUser, Name)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Document(
    Id INT PRIMARY KEY AUTO_INCREMENT,
    IdFolder INT NOT NULL,
    Name VARCHAR(255) NOT NULL,
    Markdown TEXT,
    Deleted BIT NOT NULL DEFAULT 0,
    
    FOREIGN KEY (IdFolder) REFERENCES Folder(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER ::
CREATE TRIGGER CheckMainFolderDeletion BEFORE UPDATE ON Folder
FOR EACH ROW
BEGIN
    IF (NEW.Name = 'I Miei Documenti' AND NEW.Deleted = 1) THEN
        SIGNAL SQLSTATE '01000';
    END IF;
END::
DELIMITER ;