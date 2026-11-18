<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/results.php - Results: bulk score entry per assessment, transcript.
 */
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

switch ($action) {
    case 'index':
        require_permission('results', 'view');
        $studentId = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
        $courseId  = isset($_GET['course_id'])  ? (int)$_GET['course_id']  : 0;
        $rows = result_all($studentId ?: null, null);
        // Filter by course through assessment
        if ($courseId) {
            $rows = array_filter($rows, fn($r) => (int)$r['course_id'] === $courseId);
        }
        view('results/index', ['title' => 'Results', 'results' => array_values($rows), 'courses' => course_all(), 'students' => student_all(), 'courseId' => $courseId, 'studentId' => $studentId, 'canManage' => can('results', 'create')]);
        break;

    /**
     * Bulk score entry for one assessment. Shows all enrolled students;
     * existing scores are pre-filled. Submitting upserts each row.
     */
    case 'assessment':
        require_permission('results', 'view');
        $assessment = assessment_find($id);
        if (!$assessment) { flash_set('error', 'Assessment not found.'); redirect(APP_URL . '/index.php?page=assessments'); }
        // Get all active students enrolled in the course's programme.
        $students = attendance_students_for_course((int)$assessment['course_id']);
        // Pre-load existing scores keyed by student_id.
        $existing = [];
        foreach (result_all(null, $id) as $r) { $existing[(int)$r['student_id']] = $r; }
        view('results/entry', ['title' => 'Enter Scores', 'assessment' => $assessment, 'students' => $students, 'existing' => $existing, 'canManage' => can('results', 'create')]);
        break;

    case 'save':
        require_permission('results', 'create');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=results'); }
        $assessmentId = (int)($_POST['assessment_id'] ?? 0);
        $assessment = assessment_find($assessmentId);
        if (!$assessment) { flash_set('error', 'Assessment not found.'); redirect(APP_URL . '/index.php?page=results'); }
        $scores = $_POST['score'] ?? [];
        $remarks = $_POST['remarks'] ?? [];
        $max = (float)$assessment['max_score'];
        $count = 0; $errors = [];
        foreach ($scores as $sid => $raw) {
            $raw = trim($raw);
            if ($raw === '') continue; // skip blank rows
            $score = filter_var($raw, FILTER_VALIDATE_FLOAT);
            if ($score === false || $score < 0 || $score > $max) {
                $errors[] = "Student ID $sid: score must be 0–$max.";
                continue;
            }
            result_upsert((int)$sid, $assessmentId, $score, trim($remarks[$sid] ?? '') ?: null);
            $count++;
        }
        if ($errors) flash_set('error', implode(' ', $errors));
        flash_set('success', "Scores saved for $count student(s).");
        redirect(APP_URL . '/index.php?page=results&action=assessment&id=' . $assessmentId);
        break;

    case 'delete':
        require_permission('results', 'delete');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=results'); }
        $row = result_find($id);
        if (!$row) { flash_set('error', 'Result not found.'); redirect(APP_URL . '/index.php?page=results'); }
        result_delete($id);
        flash_set('success', 'Result deleted.');
        redirect(APP_URL . '/index.php?page=results');
        break;

    case 'transcript':
        require_permission('results', 'view');
        $studentId = $id ?: (isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0);
        if (!$studentId) { flash_set('error', 'Student not specified.'); redirect(APP_URL . '/index.php?page=results'); }
        $student = student_find($studentId);
        if (!$student) { flash_set('error', 'Student not found.'); redirect(APP_URL . '/index.php?page=results'); }
        $results = results_for_student($studentId);
        $enrolments = enrolments_for_student($studentId);
        view('results/transcript', ['title' => 'Transcript', 'student' => $student, 'results' => $results, 'enrolments' => $enrolments]);
        break;

    default:
        http_response_code(404);
        view('errors/404', ['title' => 'Page not found']);
}
