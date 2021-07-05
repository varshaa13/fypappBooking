Create table Lecturer (
id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255),
name VARCHAR(255),
email VARCHAR (255),
password VARCHAR (255),
INDEX USING BTREE (username)
) ENGINE = InnoDB CHARACTER SET=utf8;


Create table Student(
id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255),
password VARCHAR (255),
name VARCHAR(255),
course VARCHAR (255),
email VARCHAR (255),
lecturerid INTEGER,
INDEX USING BTREE (username),
CONSTRAINT FOREIGN KEY (lecturerid) references Lecturer (id) 
ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET=utf8;


CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `apppurpose` varchar(255) DEFAULT NULL,
  `studentid` int(11) DEFAULT NULL,
  `timetableid` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Trigger 1
CREATE TRIGGER `Update_Status` AFTER INSERT ON `appointment` FOR EACH ROW UPDATE timetable
SET status="Booked"
WHERE id=NEW.timetableid;

-- Trigger 2
CREATE TRIGGER `Set_Appointment_Status` BEFORE INSERT ON `appointment` FOR EACH ROW SET NEW.status = "Pending";

-- Trigger 3
CREATE TRIGGER `Change_Status_On_Delete` BEFORE DELETE ON `appointment` FOR EACH ROW UPDATE timetable
SET status = "Available"
WHERE id=OLD.timetableid;


-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `startingtime` time DEFAULT NULL,
  `endingtime` time DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `lecturerid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Trigger 1
CREATE TRIGGER `Set_Status` BEFORE INSERT ON `timetable` FOR EACH ROW SET NEW.status = "Available";

