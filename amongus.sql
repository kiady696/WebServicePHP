CREATE DATABASE amongus;
-- CREATE USER kiady with password 'kiady';
GRANT CONNECT ON DATABASE trosa TO kiady;
ALTER DATABASE amongus OWNER TO kiady;
CREATE ROLE su;
ALTER ROLE su SUPERUSER;
GRANT su TO kiady;

-- UTILISATEUR

CREATE TABLE UTILISATEUR(
    id VARCHAR(50) primary key,
    nom VARCHAR(100) not null,
    username VARCHAR(50) not null,
    email VARCHAR(50) not null
);

INSERT INTO UTILISATEUR VALUES('U1','Kiady' , 'Cyan' , 'kiadyr@live.com');
INSERT INTO UTILISATEUR VALUES('U2','Douds' , 'Blue' , 'douds@gmail.com');
INSERT INTO UTILISATEUR VALUES('U3','Tsoubl' , 'Red' , 'tsoubl@yahoo.fr');
INSERT INTO UTILISATEUR VALUES('U4','Sus' , 'Green' , 'reallySus@outlook.com');



--alter table TROSA
   --add constraint FK_TROSA_REFERENCE_USER foreign key (ID_USER)
     -- references UTILISATEUR (ID)
     -- on delete restrict on update restrict;