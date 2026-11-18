-- =====================================================================
-- Lakeview Skills Institute SIMIS — Seed Data
-- ---------------------------------------------------------------------
-- Fictional sample data for demonstration ONLY. No real persons.
-- All five demo accounts use the password:  password
--
-- Import order:  schema.sql  THEN  seed.sql
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE result;
TRUNCATE TABLE attendance;
TRUNCATE TABLE payment;
TRUNCATE TABLE fee_charge;
TRUNCATE TABLE enrolment;
TRUNCATE TABLE assessment;
TRUNCATE TABLE course;
TRUNCATE TABLE student;
TRUNCATE TABLE programme;
TRUNCATE TABLE user;
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- Users (5). password_hash = password_hash('password')
-- =====================================================================
INSERT INTO user (user_id, username, password_hash, full_name, email, role, status) VALUES
(1, 'admin',      '$2y$10$ACKTsVG5SVKfJBCqlb7Lq.AbxtU18jzMgY6ox/ezp6PDrk/sund6e', 'Samuel Okello',      'admin@lakeview.ac.ug',      'administrator',     'active'),
(2, 'admissions', '$2y$10$xraUdeJUqO9Ktfp0MOGZaebJ0xH1LFJF.SM0XZ87J2TV3Ul1V16zm', 'Grace Nakato',       'admissions@lakeview.ac.ug', 'admissions_officer', 'active'),
(3, 'finance',    '$2y$10$5HzzkZMS/cKIUCIGYJ2eW.Go3nck3UfBBfiKYn7tkuTujsM3kAYXy', 'David Mugisha',      'finance@lakeview.ac.ug',    'finance_officer',    'active'),
(4, 'academic',   '$2y$10$MXxmxE1eVTvI3geqS/kc.eeA.QjJE073IWbsvus30sXnU2nMQhjZm', 'Sarah Achieng',      'academic@lakeview.ac.ug',   'academic_officer',   'active'),
(5, 'management', '$2y$10$F94fizC6yI4R/u2FUcYNFeWGkDLgdvoX2CGXXzIjIwvADYCJ80obC', 'John Baptist Mukasa','principal@lakeview.ac.ug',  'management',         'active');

-- =====================================================================
-- Programmes (3): ICT, Business Administration, Electrical Installation
-- =====================================================================
INSERT INTO programme (programme_id, programme_code, programme_name, description, duration, status) VALUES
(1, 'ICT', 'Diploma in Information & Communication Technology',
    'A two-year diploma covering computing fundamentals, programming, databases, networking and systems analysis.', '2 Years (4 Semesters)', 'active'),
(2, 'BUS', 'Diploma in Business Administration',
    'A two-year diploma in management, accounting, marketing and entrepreneurship for business careers.', '2 Years (4 Semesters)', 'active'),
(3, 'ELE', 'Certificate in Electrical Installation',
    'A one-year certificate in electrical principles, safety, wiring and installation.', '1 Year (2 Semesters)', 'active');

-- =====================================================================
-- Courses (20). Each course belongs to one programme via programme_id.
-- =====================================================================
INSERT INTO course (course_id, course_code, course_name, description, credit_units, programme_id, semester, status) VALUES
-- ICT programme (1)
(1,  'ICT101', 'Introduction to Computing',         'Foundations of computer systems and operating environments.', 3, 1, 1, 'active'),
(2,  'ICT102', 'Computer Applications',             'Office productivity applications and document processing.',   3, 1, 1, 'active'),
(3,  'ICT201', 'Programming Fundamentals',           'Introduction to structured and object-oriented programming.',  4, 1, 2, 'active'),
(4,  'ICT202', 'Web Development',                   'Building static and dynamic websites with HTML, CSS and JS.',  4, 1, 2, 'active'),
(5,  'ICT301', 'Database Systems',                  'Relational database design and SQL.',                          4, 1, 3, 'active'),
(6,  'ICT302', 'Computer Networking',               'Network principles, configuration and security.',              4, 1, 3, 'active'),
(7,  'ICT401', 'Systems Analysis and Design',      'Requirements gathering and system modelling.',                 4, 1, 4, 'active'),
(8,  'ICT402', 'IT Project',                        'Capstone project applying learnt skills.',                      6, 1, 4, 'active'),
-- Business programme (2)
(9,  'BUS101', 'Business Communication',            'Effective written and oral business communication.',          3, 2, 1, 'active'),
(10, 'BUS102', 'Principles of Management',          'Fundamentals of planning, organising and leading.',          3, 2, 1, 'active'),
(11, 'BUS201', 'Accounting Fundamentals',          'Introduction to bookkeeping and financial records.',          4, 2, 2, 'active'),
(12, 'BUS202', 'Marketing Principles',              'Principles of marketing and customer relations.',             4, 2, 2, 'active'),
(13, 'BUS301', 'Business Law',                     'Legal environment of business in Uganda.',                    3, 2, 3, 'active'),
(14, 'BUS302', 'Entrepreneurship',                 'Starting and managing a small enterprise.',                    3, 2, 3, 'active'),
(15, 'BUS401', 'Financial Accounting',             'Advanced accounting and financial statements.',               4, 2, 4, 'active'),
(16, 'BUS402', 'Project Management',               'Planning and executing business projects.',                   4, 2, 4, 'active'),
-- Electrical programme (3)
(17, 'ELE101', 'Electrical Principles',            'Basic electrical theory and circuit principles.',             4, 3, 1, 'active'),
(18, 'ELE102', 'Safety and Tools',                  'Electrical safety practices and use of hand tools.',          3, 3, 1, 'active'),
(19, 'ELE201', 'Wiring and Installation',           'Domestic and industrial wiring and installation.',            4, 3, 2, 'active'),
(20, 'ELE202', 'Electrical Machines',              'Motors, generators and transformers.',                        4, 3, 2, 'active');

-- =====================================================================
-- Students (12). Fictional Ugandan names.
-- =====================================================================
INSERT INTO student (student_id, student_no, full_name, gender, date_of_birth, nationality, phone, email, address, admission_date, status, emergency_contact_name, emergency_contact_phone) VALUES
(1,  'LVS/2023/001', 'Okello Daniel',       'male',   '2001-05-12', 'Ugandan', '+256 772 100001', 'okello.d@student.lakeview.ac.ug',     'Plot 12, Ntinda, Kampala',  '2023-01-15', 'active',    'James Okello',      '+256 772 999001'),
(2,  'LVS/2023/002', 'Namuli Joan',         'female', '2002-08-03', 'Ugandan', '+256 772 100002', 'namuli.j@student.lakeview.ac.ug',     'Najjera, Wakiso',            '2023-01-15', 'active',    'Mary Namuli',       '+256 772 999002'),
(3,  'LVS/2023/003', 'Ssempijja Ivan',      'male',   '2001-11-20', 'Ugandan', '+256 772 100003', 'ssempijja.i@student.lakeview.ac.ug',  'Mukono Town',                '2023-01-15', 'active',    'Peter Ssempijja',   '+256 772 999003'),
(4,  'LVS/2023/004', 'Atim Faith',          'female', '2002-02-14', 'Ugandan', '+256 772 100004', 'atim.f@student.lakeview.ac.ug',      'Lira City',                  '2023-01-16', 'suspended', 'Grace Atim',        '+256 772 999004'),
(5,  'LVS/2023/005', 'Kamoga Peter',        'male',   '2001-07-30', 'Ugandan', '+256 772 100005', 'kamoga.p@student.lakeview.ac.ug',     'Entebbe Road',               '2023-01-16', 'active',    'Steven Kamoga',     '+256 772 999005'),
(6,  'LVS/2023/006', 'Nansubuga Rose',      'female', '2002-04-22', 'Ugandan', '+256 772 100006', 'nansubuga.r@student.lakeview.ac.ug',  'Kira Municipality',          '2023-01-16', 'active',    'Janet Nansubuga',  '+256 772 999006'),
(7,  'LVS/2023/007', 'Opio Moses',          'male',   '2001-09-09', 'Ugandan', '+256 772 100007', 'opio.m@student.lakeview.ac.ug',      'Gulu City',                  '2023-01-17', 'active',    'Charles Opio',     '+256 772 999007'),
(8,  'LVS/2024/008', 'Akello Patience',     'female', '2003-01-18', 'Ugandan', '+256 772 100008', 'akello.p@student.lakeview.ac.ug',     'Lira City',                  '2024-02-05', 'active',    'Florence Akello',   '+256 772 999008'),
(9,  'LVS/2023/009', 'Wabwire Joseph',      'male',   '2001-03-27', 'Ugandan', '+256 772 100009', 'wabwire.j@student.lakeview.ac.ug',    'Mbale Town',                 '2023-01-17', 'active',    'Patrick Wabwire',  '+256 772 999009'),
(10, 'LVS/2023/010', 'Nabirye Sarah',       'female', '2002-06-15', 'Ugandan', '+256 772 100010', 'nabirye.s@student.lakeview.ac.ug',    'Iganga Town',                '2023-01-18', 'active',    'Ruth Nabirye',      '+256 772 999010'),
(11, 'LVS/2022/011', 'Tumusiime Eric',      'male',   '2000-12-05', 'Ugandan', '+256 772 100011', 'tumusiime.e@student.lakeview.ac.ug', 'Kabale Town',                '2022-02-14', 'graduated', 'Benedict Tumusiime','+256 772 999011'),
(12, 'LVS/2022/012', 'Kembabazi Lydia',     'female', '2000-10-22', 'Ugandan', '+256 772 100012', 'kembabazi.l@student.lakeview.ac.ug',  'Mbarara City',               '2022-02-14', 'withdrawn', 'Teddy Kembabazi',   '+256 772 999012');

-- =====================================================================
-- Enrolments (12). student <-> programme per academic year/semester.
-- =====================================================================
INSERT INTO enrolment (enrolment_id, student_id, programme_id, academic_year, semester, enrolment_date, status) VALUES
(1,  1,  1, '2024', 1, '2024-02-05', 'active'),
(2,  2,  1, '2024', 1, '2024-02-05', 'active'),
(3,  3,  1, '2024', 1, '2024-02-05', 'active'),
(4,  4,  1, '2024', 1, '2024-02-05', 'suspended'),
(5,  5,  2, '2024', 1, '2024-02-05', 'active'),
(6,  6,  2, '2024', 1, '2024-02-05', 'active'),
(7,  7,  2, '2024', 1, '2024-02-05', 'active'),
(8,  8,  2, '2024', 1, '2024-02-05', 'active'),
(9,  9,  3, '2024', 1, '2024-02-05', 'active'),
(10, 10, 3, '2024', 1, '2024-02-05', 'active'),
(11, 11, 1, '2022', 4, '2022-02-14', 'graduated'),
(12, 12, 2, '2022', 1, '2022-02-14', 'withdrawn');

-- =====================================================================
-- Fee charges (12). Tuition per programme + a few other charge types.
-- =====================================================================
INSERT INTO fee_charge (fee_charge_id, student_id, amount, description, charge_type, academic_year, semester, due_date, status) VALUES
(1,  1, 800000, 'Tuition - ICT Semester 1 2024',       'tuition',     '2024', 1, '2024-03-31', 'paid'),
(2,  2, 800000, 'Tuition - ICT Semester 1 2024',       'tuition',     '2024', 1, '2024-03-31', 'partial'),
(3,  3, 800000, 'Tuition - ICT Semester 1 2024',       'tuition',     '2024', 1, '2024-03-31', 'paid'),
(4,  4, 800000, 'Tuition - ICT Semester 1 2024',       'tuition',     '2024', 1, '2024-03-31', 'partial'),
(5,  5, 700000, 'Tuition - Business Semester 1 2024', 'tuition',     '2024', 1, '2024-03-31', 'paid'),
(6,  6, 700000, 'Tuition - Business Semester 1 2024', 'tuition',     '2024', 1, '2024-03-31', 'partial'),
(7,  7, 700000, 'Tuition - Business Semester 1 2024', 'tuition',     '2024', 1, '2024-03-31', 'pending'),
(8,  9, 600000, 'Tuition - Electrical Semester 1 2024','tuition',    '2024', 1, '2024-03-31', 'paid'),
(9,  10,600000, 'Tuition - Electrical Semester 1 2024','tuition',    '2024', 1, '2024-03-31', 'partial'),
(10, 1, 100000, 'Registration Fee 2024',                'registration','2024', 1, '2024-02-15', 'paid'),
(11, 8, 700000, 'Tuition - Business Semester 1 2024', 'tuition',     '2024', 1, '2024-03-31', 'partial'),
(12, 5, 50000,  'Library Fee 2024',                     'library',     '2024', 1, '2024-03-31', 'pending');

-- =====================================================================
-- Payments (10). Allocated to fee charges; balance = charge - payments.
-- =====================================================================
INSERT INTO payment (payment_id, student_id, fee_charge_id, amount, payment_date, payment_method, reference_no) VALUES
(1,  1,  1,  800000, '2024-02-10', 'cash',          'RCT-2024-0001'),
(2,  2,  2,  500000, '2024-02-12', 'mobile_money',  'RCT-2024-0002'),
(3,  3,  3,  800000, '2024-02-15', 'bank_transfer', 'RCT-2024-0003'),
(4,  4,  4,  200000, '2024-02-20', 'cash',          'RCT-2024-0004'),
(5,  5,  5,  700000, '2024-02-11', 'bank_transfer', 'RCT-2024-0005'),
(6,  6,  6,  400000, '2024-02-18', 'mobile_money',  'RCT-2024-0006'),
(7,  9,  8,  600000, '2024-02-22', 'cash',          'RCT-2024-0007'),
(8,  10, 9,  300000, '2024-02-25', 'mobile_money',  'RCT-2024-0008'),
(9,  1,  10, 100000, '2024-02-09', 'cash',          'RCT-2024-0009'),
(10, 8,  11, 350000, '2024-03-01', 'mobile_money',  'RCT-2024-0010');

-- =====================================================================
-- Assessments (6) for ICT101, ICT102, BUS101 (CA out of 40, Exam out of 60).
-- =====================================================================
INSERT INTO assessment (assessment_id, course_id, title, assessment_type, max_score, date, academic_year, semester) VALUES
(1, 1, 'Continuous Assessment', 'ca',   40, '2024-03-15', '2024', 1),
(2, 1, 'Final Examination',    'exam', 60, '2024-05-20', '2024', 1),
(3, 2, 'Continuous Assessment', 'ca',   40, '2024-03-18', '2024', 1),
(4, 2, 'Final Examination',    'exam', 60, '2024-05-22', '2024', 1),
(5, 9, 'Continuous Assessment', 'ca',   40, '2024-03-20', '2024', 1),
(6, 9, 'Final Examination',    'exam', 60, '2024-05-24', '2024', 1);

-- =====================================================================
-- Results (20). score relative to assessment.max_score; grade by demo bands.
-- =====================================================================
INSERT INTO result (result_id, student_id, assessment_id, score, grade, remarks) VALUES
-- ICT101 (course 1)
(1,  1, 1, 32, 'A', 'Distinction'),
(2,  1, 2, 48, 'A', 'Distinction'),
(3,  2, 1, 28, 'B', 'Credit'),
(4,  2, 2, 42, 'B', 'Credit'),
(5,  3, 1, 24, 'C', 'Pass'),
(6,  3, 2, 30, 'D', 'Marginal Pass'),
(7,  4, 1, 20, 'D', 'Marginal Pass'),
(8,  4, 2, 25, 'F', 'Fail'),
-- ICT102 (course 2)
(9,  1, 3, 34, 'A', 'Distinction'),
(10, 1, 4, 50, 'A', 'Distinction'),
(11, 2, 3, 30, 'B', 'Credit'),
(12, 2, 4, 40, 'C', 'Pass'),
(13, 3, 3, 22, 'D', 'Marginal Pass'),
(14, 3, 4, 28, 'F', 'Fail'),
-- BUS101 (course 9)
(15, 5, 5, 33, 'A', 'Distinction'),
(16, 5, 6, 51, 'A', 'Distinction'),
(17, 6, 5, 27, 'C', 'Pass'),
(18, 6, 6, 39, 'C', 'Pass'),
(19, 7, 5, 18, 'F', 'Fail'),
(20, 7, 6, 22, 'F', 'Fail');

-- =====================================================================
-- Attendance (22). recorded by the Academic Officer (user 4).
-- =====================================================================
INSERT INTO attendance (attendance_id, student_id, course_id, date, status, remarks, recorded_by) VALUES
-- ICT101 attendance (students 1-4)
(1,  1, 1, '2024-03-04', 'present', NULL,       4),
(2,  1, 1, '2024-03-06', 'present', NULL,       4),
(3,  1, 1, '2024-03-08', 'present', NULL,       4),
(4,  1, 1, '2024-03-11', 'present', NULL,       4),
(5,  2, 1, '2024-03-04', 'present', NULL,       4),
(6,  2, 1, '2024-03-06', 'absent',  'Sick',     4),
(7,  2, 1, '2024-03-08', 'present', NULL,       4),
(8,  2, 1, '2024-03-11', 'late',    'Late bus', 4),
(9,  3, 1, '2024-03-04', 'present', NULL,       4),
(10, 3, 1, '2024-03-06', 'late',    'Late',     4),
(11, 3, 1, '2024-03-08', 'present', NULL,       4),
(12, 3, 1, '2024-03-11', 'present', NULL,       4),
(13, 4, 1, '2024-03-04', 'absent',  NULL,       4),
(14, 4, 1, '2024-03-06', 'absent',  NULL,       4),
(15, 4, 1, '2024-03-08', 'excused', 'Permission', 4),
(16, 4, 1, '2024-03-11', 'absent',  NULL,       4),
-- BUS101 attendance (students 5-7)
(17, 5, 9, '2024-03-05', 'present', NULL,       4),
(18, 5, 9, '2024-03-07', 'present', NULL,       4),
(19, 6, 9, '2024-03-05', 'present', NULL,       4),
(20, 6, 9, '2024-03-07', 'absent',  NULL,       4),
(21, 7, 9, '2024-03-05', 'absent',  NULL,       4),
(22, 7, 9, '2024-03-07', 'absent',  NULL,       4);
