<?php
/**
 * config/grades.php
 * Configurable grading bands for the SIMIS prototype.
 *
 * IMPORTANT: These are DEMO bands used only to illustrate grading logic.
 * They are NOT an official institutional grading policy of Lakeview Skills
 * Institute. Adjust the bands here to change how scores map to grades.
 *
 * A score is graded against its percentage of the assessment max_score,
 * checking bands from highest to lowest; the first band whose 'min' the
 * percentage meets is returned.
 */

function grade_config(): array {
    static $cfg = null;
    if ($cfg === null) {
        $cfg = [
            'bands' => [
                ['min' => 80, 'grade' => 'A', 'remark' => 'Distinction'],
                ['min' => 70, 'grade' => 'B', 'remark' => 'Credit'],
                ['min' => 60, 'grade' => 'C', 'remark' => 'Pass'],
                ['min' => 50, 'grade' => 'D', 'remark' => 'Marginal Pass'],
                ['min' => 0,  'grade' => 'F', 'remark' => 'Fail'],
            ],
            'pass_mark' => 50,
        ];
    }
    return $cfg;
}

/**
 * Map a raw score to a grade band, relative to the assessment's max score.
 * @return array{grade:string,remark:string}
 */
function grade_for_score(float $score, float $max = 100): array {
    $pct = $max > 0 ? ($score / $max) * 100 : 0;
    foreach (grade_config()['bands'] as $band) {
        if ($pct >= $band['min']) {
            return ['grade' => $band['grade'], 'remark' => $band['remark']];
        }
    }
    return ['grade' => 'F', 'remark' => 'Fail'];
}

/** Pass mark (percentage) used in pass-rate reports. */
function pass_mark(): int {
    return grade_config()['pass_mark'];
}
