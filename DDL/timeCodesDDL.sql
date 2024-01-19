-- CREATES BIZNET DATABASE
CREATE DATABASE TIMECODES;

-- SETS BIZNET AS DEFAULT DB
USE TIMECODES;

-- CREATES USERINFO TABLE
CREATE TABLE USERINFO (
	USERNAME VARCHAR(20),
    FIRSTNAME VARCHAR(20),
    LASTNAME VARCHAR(20),
    PHONE VARCHAR(20),
    EMAIL VARCHAR(20),
    PASSWORD VARCHAR(100),
    SECURITYACCESS VARCHAR(20)
);

-- CREATES TABLE TO HOLD USER SECURITY
CREATE TABLE USERSECURITY (
	USERNAME VARCHAR(20),
    SECURITYACCESS VARCHAR(20)
);

-- CREATES TIMECODES TABLE
CREATE TABLE TIMECODES (
	TIMECODE VARCHAR(20),
    DESCRIPTION VARCHAR(100),
    ISACTIVE INT
);

-- CREATE TIMECODESLOGGING TABLE
CREATE TABLE TIMECODESLOGGING (
	USERNAME VARCHAR(20),
    TIMECODE VARCHAR(20),
    TIMECODESTART DATETIME,
    TIMECODEEND DATETIME
);

-- LISTS
CREATE TABLE TYPELISTS (
	LISTFIELD VARCHAR(20),
    TYPEFIELD INT
);

INSERT INTO TYPELISTS (LISTFIELD, TYPEFIELD) VALUES ('ADMIN', 1);
INSERT INTO TYPELISTS (LISTFIELD, TYPEFIELD) VALUES ('WORKER', 1);
INSERT INTO USERINFO (USERNAME, PASSWORD) VALUES ('admin', '$2y$10$DDKmYtILviiRZO317TO6CeDNT5axe1vDbN3e2VBYYhbWyDdOBaIYC');
