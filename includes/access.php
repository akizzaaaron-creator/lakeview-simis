<?php
/**
 * includes/access.php
 * Role-Based Access Control (RBAC).
 *
 * Permissions are enforced on the SERVER SIDE by require_permission() in every
 * controller action. Hiding UI buttons is only a convenience — the server
 * guard is authoritative. Administrator always has full access.
 *
 * Permission values per role per module:
 *   'manage' = full CRUD (view, create, edit, delete)
 *   'view'   = read only (list/show)
 *   null     = no access
 */

function permissions(): array {
    return [
        'dashboard'  => [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>'view',  ROLE_FINANCE=>'view',  ROLE_ACADEMIC=>'view',  ROLE_MANAGEMENT=>'view'  ],
        'students'   => [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>'manage', ROLE_FINANCE=>'view',  ROLE_ACADEMIC=>'view',  ROLE_MANAGEMENT=>'view'  ],
        'programmes' => [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>'manage', ROLE_FINANCE=>'view',  ROLE_ACADEMIC=>'view',  ROLE_MANAGEMENT=>'view'  ],
        'courses'    => [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>'manage', ROLE_FINANCE=>'view',  ROLE_ACADEMIC=>'manage', ROLE_MANAGEMENT=>'view'  ],
        'enrolments' => [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>'manage', ROLE_FINANCE=>'view',  ROLE_ACADEMIC=>'view',  ROLE_MANAGEMENT=>'view'  ],
        'fee_charges'=> [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>'view',   ROLE_FINANCE=>'manage', ROLE_ACADEMIC=>'view', ROLE_MANAGEMENT=>'view'  ],
        'payments'   => [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>'view',   ROLE_FINANCE=>'manage', ROLE_ACADEMIC=>'view', ROLE_MANAGEMENT=>'view'  ],
        'attendance' => [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>'view',   ROLE_FINANCE=>'view',   ROLE_ACADEMIC=>'manage', ROLE_MANAGEMENT=>'view' ],
        'assessments'=> [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>'view',   ROLE_FINANCE=>'view',   ROLE_ACADEMIC=>'manage', ROLE_MANAGEMENT=>'view' ],
        'results'    => [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>'view',   ROLE_FINANCE=>'view',   ROLE_ACADEMIC=>'manage', ROLE_MANAGEMENT=>'view' ],
        'users'      => [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>null,     ROLE_FINANCE=>null,     ROLE_ACADEMIC=>null,    ROLE_MANAGEMENT=>null    ],
        'reports'    => [ROLE_ADMIN=>'manage', ROLE_ADMISSIONS=>'view',   ROLE_FINANCE=>'view',   ROLE_ACADEMIC=>'view',   ROLE_MANAGEMENT=>'manage'],
    ];
}

/**
 * @param string $module module key (e.g. 'students')
 * @param string $action 'view' | 'create' | 'edit' | 'delete'
 */
function can(string $module, string $action = 'view'): bool {
    $user = current_user();
    if (!$user) return false;
    $role = $user['role'];
    if ($role === ROLE_ADMIN) return true;            // administrator = full access
    $perm = permissions()[$module][$role] ?? null;
    if ($perm === 'manage') return true;              // any action including view
    if ($perm === 'view' && $action === 'view') return true;
    return false;
}

/** Server-side guard. Renders a 403 page and stops execution if denied. */
function require_permission(string $module, string $action = 'view'): void {
    if (!can($module, $action)) {
        http_response_code(403);
        view('errors/403', ['title' => 'Access denied']);
        exit;
    }
}
