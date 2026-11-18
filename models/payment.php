<?php
/** models/payment.php — Fee payment data access. */

function payment_all(?int $studentId = null, ?string $method = null): array {
    $sql = "SELECT p.*, s.student_no, s.full_name, fc.description AS charge_desc, fc.charge_type
            FROM payment p
            JOIN student s ON s.student_id = p.student_id
            LEFT JOIN fee_charge fc ON fc.fee_charge_id = p.fee_charge_id";
    $w = []; $p = [];
    if ($studentId) { $w[] = "p.student_id = ?"; $p[] = $studentId; }
    if ($method) { $w[] = "p.payment_method = ?"; $p[] = $method; }
    if ($w) $sql .= " WHERE " . implode(' AND ', $w);
    $sql .= " ORDER BY p.payment_date DESC, p.payment_id DESC";
    $stmt = db()->prepare($sql);
    $stmt->execute($p);
    return $stmt->fetchAll();
}

function payment_find(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM payment WHERE payment_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function payment_create(array $d): int {
    $stmt = db()->prepare("INSERT INTO payment (student_id, fee_charge_id, amount, payment_date, payment_method, reference_no) VALUES (?,?,?,?,?,?)");
    $feeCharge = !empty($d['fee_charge_id']) ? (int)$d['fee_charge_id'] : null;
    $stmt->execute([
        (int)$d['student_id'], $feeCharge, (float)$d['amount'],
        $d['payment_date'], $d['payment_method'], $d['reference_no'] ?: null,
    ]);
    $id = (int) db()->lastInsertId();
    if ($feeCharge) fee_charge_recompute_status($feeCharge);
    return $id;
}

function payment_update(int $id, array $d): void {
    // Recompute the previously linked charge before changing the link.
    $old = payment_find($id);
    $stmt = db()->prepare("UPDATE payment SET student_id=?, fee_charge_id=?, amount=?, payment_date=?, payment_method=?, reference_no=? WHERE payment_id=?");
    $feeCharge = !empty($d['fee_charge_id']) ? (int)$d['fee_charge_id'] : null;
    $stmt->execute([
        (int)$d['student_id'], $feeCharge, (float)$d['amount'],
        $d['payment_date'], $d['payment_method'], $d['reference_no'] ?: null, $id,
    ]);
    if ($old && $old['fee_charge_id']) fee_charge_recompute_status((int)$old['fee_charge_id']);
    if ($feeCharge) fee_charge_recompute_status($feeCharge);
}

function payment_delete(int $id): void {
    $p = payment_find($id);
    $stmt = db()->prepare("DELETE FROM payment WHERE payment_id = ?");
    $stmt->execute([$id]);
    if ($p && $p['fee_charge_id']) fee_charge_recompute_status((int)$p['fee_charge_id']);
}

/** Total amount paid by a student. */
function payment_total(int $studentId): float {
    $stmt = db()->prepare("SELECT COALESCE(SUM(amount),0) FROM payment WHERE student_id = ?");
    $stmt->execute([$studentId]);
    return (float) $stmt->fetchColumn();
}
