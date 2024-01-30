-- Delete tables if they exist. Note I remove them in the opposite order that I add them.
DROP TABLE IF EXISTS billing;
DROP TABLE IF EXISTS takes;
DROP TABLE IF EXISTS lot;
DROP TABLE IF EXISTS vaccinationSite;
DROP TABLE IF EXISTS vaccine;
DROP TABLE IF EXISTS allergy;
DROP TABLE IF EXISTS uninsured_patient;
DROP TABLE IF EXISTS insured_patient;
DROP TABLE IF EXISTS patient;

CREATE TABLE patient (
    patientId CHAR(32),
    fName VARCHAR(255) NOT NULL,
    mInitial VARCHAR(1),
    lName VARCHAR(255) NOT NULL,
    dob DATE,
    weight NUMERIC(12, 2),

    PRIMARY KEY(patientId)
);

CREATE TABLE insured_patient (
    patientId CHAR(32),
    company VARCHAR(255) NOT NULL,
    copay NUMERIC(12, 2),
    
    PRIMARY KEY(patientId),
    FOREIGN KEY(patientId) REFERENCES patient(patientId) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE uninsured_patient (
    patientId CHAR(32),
    paymentMethod VARCHAR(255) NOT NULL,
    addrStreet VARCHAR(255) NOT NULL,
    addrCity VARCHAR(255) NOT NULL,
    addrState CHAR(2) NOT NULL,
    addrZip VARCHAR(10) NOT NULL,
    
    PRIMARY KEY(patientId),
    FOREIGN KEY(patientId) REFERENCES patient(patientId) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE allergy (
    patientId CHAR(32),
    allergyDesc VARCHAR(255) NOT NULL,

    PRIMARY KEY(patientId, allergyDesc),
    FOREIGN KEY(patientId) REFERENCES patient(patientId) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE vaccine (
    scientificName VARCHAR(255),
    disease VARCHAR(255),
    noDoses INT,

    PRIMARY KEY(scientificName)
);

CREATE TABLE vaccinationSite (
    siteName VARCHAR(255),
    addrStreet VARCHAR(255) NOT NULL,
    addrCity VARCHAR(255) NOT NULL,
    addrState VARCHAR(2) NOT NULL,
    addrZip VARCHAR(10) NOT NULL,

    PRIMARY KEY(siteName)
);

CREATE TABLE lot (
    scientificName VARCHAR(255),
    lotNumber INT NOT NULL,
    manufacturerPlace VARCHAR(255),
    expiration DATE,

    PRIMARY KEY(scientificName, lotNumber),
    FOREIGN KEY(scientificName) REFERENCES vaccine(scientificName) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE takes (
    patientId CHAR(32),
    siteName VARCHAR(255) NOT NULL,
    scientificName VARCHAR(255) NOT NULL,
    dateTaken date,

    PRIMARY KEY(patientId, siteName, scientificName, dateTaken),
    FOREIGN KEY(patientId) REFERENCES patient(patientId) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(siteName) REFERENCES vaccinationSite(siteName) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(scientificName) REFERENCES vaccine(scientificName) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE billing (
    patientId CHAR(32) NOT NULL,
    siteName VARCHAR(255) NOT NULL,

    PRIMARY KEY(patientId, siteName),
    FOREIGN KEY(patientId) REFERENCES uninsured_patient(patientId) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(siteName) REFERENCES vaccinationSite(siteName) ON DELETE CASCADE ON UPDATE CASCADE
);


