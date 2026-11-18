<?php
/** models/fee_charge.php — Fee charge data access. */

function fee_charge_all(?int $studentId = null, ?string $status = null): array {
    $sql = "SELECT fc.*, s.student_no, s.full_name
            FROM fee_charge fc JOIN student s ON s.student_id = fc.student_id";
    $w = []; $p = [];
    if ($studentId) { $w[] = "fc.student_id = ?"; $p[] = $studentId; }
    if ($status) { $w[] = "fc.status = ?"; $p[] = $status; }
    if ($w) $sql .= " WHERE " . implode(' AND ', $w);
    $sql .= " ORDER BY fc.due_date DESC, fc.fee_charge_id DESC";
    $stmt = db()->prepare($sql);
    $stmt->execute($p);
    return $stmt->fetchAll();
}

function fee_charge_find(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM fee_charge WHERE fee_charge_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function fee_charge_create(array $d): int {
    $stmt = db()->prepare("INSERT INTO fee_charge (student_id, amount, description, charge_type, academic_year, semester, due_date, status) VALUES (?,?,?,?,?,?,?,?)");
    $sem = (isset($d['semester']) && $d['semester'] !== '') ? (int)$d['semester'] : null;
    $stmt->execute([
        (int)$d['student_id'], (float)$d['amount'], $d['description'] ?: null,
        $d['charge_type'], $d['academic_year'] ?: null, $sem, $d['due_date'] ?: null, $d['status'],
    ]);
    return (int) db()->lastInsertId();
}

function fee_charge_update(int $id, array $d): void {
    $stmt = db()->prepare("UPDATE fee_charge SET student_id=?, amount=?, description=?, charge_type=?, academic_year=?, semester=?, due_date=?, status=? WHERE fee_charge_id=?");
    $sem = (isset($d['semester']) && $d['semester'] !== '') ? (int)$d['semester'] : null;
    $stmt->execute([
        (int)$d['student_id'], (float)$d['amount'], $d['description'] ?: null,
        $d['charge_type'], $d['academic_year'] ?: null, $sem, $d['due_date'] ?: null, $d['status'], $id,
    ]);
}

function fee_charge_delete(int $id): void {
    $stmt = db()->prepare("DELETE FROM fee_charge WHERE fee_charge_id = ?");
    $stmt->execute([$id]);
}

/** Total amount charged to a student. */
function fee_charge_total(int $studentId): float {
    $stmt = db()->prepare("SELECT COALESCE(SUM(amount),0) FROM fee_charge WHERE student_id = ?");
    $stmt->execute([$studentId]);
    return (float) $stmt->fetchColumn();
}

/** Recompute a charge's status from its linked payments and persist it. */
function fee_charge_recompute_status(int $chargeId): string {
    $charge = fee_charge_find($chargeId);
    if (!$charge) return 'pending';
    $stmt = db()->prepare("SELECT COALESCE(SUM(amount),0) FROM payment WHERE fee_charge_id = ?");
    $stmt->execute([$chargeId]);
    $paid = (float) $stmt->fetchColumn();
    $status = $paid >= (float)$charge['amount'] ? 'paid' : ($paid > 0 ? 'partial' : 'pending');
    $upd = db()->prepare("UPDATE fee_charge SET status = ? WHERE fee_charge_id = ?");
    $upd->execute([$status, $chargeId]);
    return $status;
}
