-- =====================================================================
-- Lakeview Skills Institute SIMIS — Database Schema
-- ---------------------------------------------------------------------
-- Ten entities (matches the academic ERD):
--   user, programme, course, student, enrolment, fee_charge, payment,
--   attendance, assessment, result
--
-- Relationships implemented via foreign keys:
--   student  1—N enrolment            programme 1—N enrolment
--   programme 1—N course (course.programme_id)
--   student  1—N fee_charge
--   fee_charge 1—N payment             student 1—N payment
--   student + course -> attendance      user -> attendance (recorded_by)
--   course 1—N assessment
--   assessment + student -> result
--
-- Target: MySQL 8 / MariaDB 10+ (InnoDB, utf8mb4).
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS result;
DROP TABLE IF EXISTS attendance;
DROP TABLE IF EXISTS payment;
DROP TABLE IF EXISTS fee_charge;
DROP TABLE IF EXISTS enrolment;
DROP TABLE IF EXISTS assessment;
DROP TABLE IF EXISTS course;
DROP TABLE IF EXISTS student;
DROP TABLE IF EXISTS programme;
DROP TABLE IF EXISTS user;

-- 1. User ----------------------------------------------------------------
CREATE TABLE user (
  user_id        INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username       VARCHAR(50)  NOT NULL,
  password_hash  VARCHAR(255)  NOT NULL,
  full_name      VARCHAR(150)  NOT NULL,
  email          VARCHAR(150)  DEFAULT NULL,
  role           VARCHAR(30)   NOT NULL COMMENT 'administrator|admissions_officer|finance_officer|academic_officer|management',
  status         VARCHAR(20)   NOT NULL DEFAULT 'active' COMMENT 'active|inactive',
  created_at     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='System users and their roles';

-- 2. Programme ----------------------------------------------------------
CREATE TABLE programme (
  programme_id   INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  programme_code  VARCHAR(20)  NOT NULL,
  programme_name  VARCHAR(150) NOT NULL,
  description    TEXT,
  duration       VARCHAR(50),
  status         VARCHAR(20)  NOT NULL DEFAULT 'active' COMMENT 'active|inactive',
  created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_programme_code (programme_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Training programmes offered by the institute';

-- 3. Course -------------------------------------------------------------
CREATE TABLE course (
  course_id      INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  course_code    VARCHAR(20)  NOT NULL,
  course_name    VARCHAR(150) NOT NULL,
  description    TEXT,
  credit_units   INT          NOT NULL DEFAULT 0,
  programme_id   INT UNSIGNED NOT NULL COMMENT 'Each course belongs to one programme',
  semester       INT          NOT NULL DEFAULT 1,
  status         VARCHAR(20)  NOT NULL DEFAULT 'active',
  created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_course_code (course_code),
  CONSTRAINT fk_course_programme
    FOREIGN KEY (programme_id) REFERENCES programme (programme_id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Courses (subjects) that belong to a programme';

-- 4. Student ------------------------------------------------------------
CREATE TABLE student (
  student_id             INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  student_no             VARCHAR(20)  NOT NULL COMMENT 'Registration number e.g. LVS/2023/001',
  full_name              VARCHAR(150) NOT NULL,
  gender                 VARCHAR(10)  DEFAULT NULL COMMENT 'male|female|other',
  date_of_birth          DATE         DEFAULT NULL,
  nationality            VARCHAR(80)  DEFAULT 'Ugandan',
  phone                  VARCHAR(30)  DEFAULT NULL,
  email                  VARCHAR(150) DEFAULT NULL,
  address                VARCHAR(255) DEFAULT NULL,
  admission_date         DATE         NOT NULL,
  status                 VARCHAR(20)  NOT NULL DEFAULT 'active' COMMENT 'active|suspended|graduated|withdrawn',
  emergency_contact_name VARCHAR(150) DEFAULT NULL,
  emergency_contact_phone VARCHAR(30)  DEFAULT NULL,
  created_at             DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_student_no (student_no)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Student master records';

-- 5. Enrolment ----------------------------------------------------------
CREATE TABLE enrolment (
  enrolment_id   INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  student_id     INT UNSIGNED NOT NULL,
  programme_id   INT UNSIGNED NOT NULL,
  academic_year  VARCHAR(20)  NOT NULL,
  semester       INT          NOT NULL DEFAULT 1,
  enrolment_date DATE         NOT NULL,
  status         VARCHAR(20)  NOT NULL DEFAULT 'active',
  created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_enrolment (student_id, programme_id, academic_year, semester),
  CONSTRAINT fk_enrol_student
    FOREIGN KEY (student_id)   REFERENCES student (student_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_enrol_programme
    FOREIGN KEY (programme_id) REFERENCES programme (programme_id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Links a student to a programme per academic year/semester';

-- 6. FeeCharge ----------------------------------------------------------
CREATE TABLE fee_charge (
  fee_charge_id  INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  student_id    INT UNSIGNED NOT NULL,
  amount         DECIMAL(12,2) NOT NULL DEFAULT 0,
  description    VARCHAR(255),
  charge_type    VARCHAR(30)   NOT NULL DEFAULT 'tuition' COMMENT 'tuition|registration|examination|library|other',
  academic_year  VARCHAR(20),
  semester       INT,
  due_date       DATE,
  status         VARCHAR(20)   NOT NULL DEFAULT 'pending' COMMENT 'pending|partial|paid',
  created_at     DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_charge_student
    FOREIGN KEY (student_id) REFERENCES student (student_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Fees charged to a student';

-- 7. Payment ------------------------------------------------------------
CREATE TABLE payment (
  payment_id     INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  student_id     INT UNSIGNED NOT NULL,
  fee_charge_id  INT UNSIGNED DEFAULT NULL COMMENT 'Optional allocation of payment to a specific charge',
  amount         DECIMAL(12,2) NOT NULL DEFAULT 0,
  payment_date   DATE         NOT NULL,
  payment_method VARCHAR(30)  NOT NULL DEFAULT 'cash' COMMENT 'cash|bank_transfer|mobile_money|cheque',
  reference_no   VARCHAR(80),
  created_at     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pay_student
    FOREIGN KEY (student_id)    REFERENCES student (student_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_pay_charge
    FOREIGN KEY (fee_charge_id) REFERENCES fee_charge (fee_charge_id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Fee payments received from students';

-- 8. Attendance ---------------------------------------------------------
CREATE TABLE attendance (
  attendance_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  student_id    INT UNSIGNED NOT NULL,
  course_id     INT UNSIGNED NOT NULL,
  date          DATE         NOT NULL,
  status        VARCHAR(20)  NOT NULL DEFAULT 'present' COMMENT 'present|absent|late|excused',
  remarks       VARCHAR(255),
  recorded_by   INT UNSIGNED DEFAULT NULL,
  created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_attendance (student_id, course_id, date),
  CONSTRAINT fk_att_student
    FOREIGN KEY (student_id)  REFERENCES student (student_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_att_course
    FOREIGN KEY (course_id)   REFERENCES course (course_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_att_user
    FOREIGN KEY (recorded_by) REFERENCES user (user_id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Daily attendance per student per course';

-- 9. Assessment ---------------------------------------------------------
CREATE TABLE assessment (
  assessment_id   INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  course_id       INT UNSIGNED NOT NULL,
  title           VARCHAR(150) NOT NULL,
  assessment_type VARCHAR(30)  NOT NULL DEFAULT 'ca' COMMENT 'ca|exam|assignment',
  max_score       DECIMAL(6,2) NOT NULL DEFAULT 100,
  date            DATE,
  academic_year   VARCHAR(20),
  semester        INT,
  created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_assess_course
    FOREIGN KEY (course_id) REFERENCES course (course_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Assessments belonging to a course';

-- 10. Result ------------------------------------------------------------
CREATE TABLE result (
  result_id      INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  student_id     INT UNSIGNED NOT NULL,
  assessment_id  INT UNSIGNED NOT NULL,
  score          DECIMAL(6,2) NOT NULL DEFAULT 0,
  grade          VARCHAR(5),
  remarks        VARCHAR(255),
  created_at     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_result (student_id, assessment_id),
  CONSTRAINT fk_res_student
    FOREIGN KEY (student_id)    REFERENCES student (student_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_res_assessment
    FOREIGN KEY (assessment_id) REFERENCES assessment (assessment_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='A students score on an assessment';

SET FOREIGN_KEY_CHECKS = 1;
