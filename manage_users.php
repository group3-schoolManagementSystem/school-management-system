<?php
// Display success/error messages
if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>
<?php
// manage_users.php
require_once 'auth.php'; // ← This now includes session and db.php

// Rest of your code (no need for session_start() or require_once 'db.php' here)
?>

<?php

require_once 'auth.php';

// Fetch all users except current admin
$stmt = $pdo->prepare("SELECT * FROM users WHERE id != ?");
$stmt->execute([$_SESSION['user_id']]);
$users = $stmt->fetchAll();

// After receiving POST data
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$_POST['email']]);

if ($stmt->fetch()) {
    header("Location: manage_users.php?error=Email+already+exists");
    exit();
}

// Proceed with insertion

// After successful deletion
$logStmt = $pdo->prepare("INSERT INTO audit_logs 
    (user_id, action, target_user_id)
    VALUES (?, 'delete_user', ?)");
$logStmt->execute([$_SESSION['user_id'], $_GET['id']]);
?>

<!-- Display users in a table -->
<table class="table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>
    <td>
    <!-- Existing buttons -->
    <button class="btn btn-sm btn-info reset-pwd-btn" 
            data-userid="<?= $user['id'] ?>">
        <i class="fas fa-key"></i>
    </button>
</td>

  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
      <td><?= $user['full_name'] ?></td>
      <td><?= $user['email'] ?></td>
      <td><?= $user['role'] ?></td>
      <td>
        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Add User Form -->
<form method="POST" action="add_user.php">
  <input type="text" name="full_name" placeholder="Full Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <select name="role">
    <option value="admin">Admin</option>
    <option value="teacher">Teacher</option>
    <option value="student">Student</option>
  </select>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit" class="btn btn-success">Add User</button>
</form>
<?php
require_once 'auth.php';
// Include your PHP logic to fetch users here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .action-btns { min-width: 120px; }
        .user-avatar { width: 40px; height: 40px; object-fit: cover; }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (Reuse your dashboard sidebar) -->
            
            <!-- Main Content -->
            <main class="col-md-10 p-4">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">
                        <i class="fas fa-users-cog me-2"></i>Manage Users
                    </h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus-circle me-2"></i>Add New User
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search users...">
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <img src="placeholder-user.jpg" class="rounded-circle user-avatar" alt="User Photo">
                                    </td>
                                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= getRoleBadgeColor($user['role']) ?>">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                    <td class="action-btns">
                                        <button class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editUserModal"
                                                data-id="<?= $user['id'] ?>"
                                                data-name="<?= $user['full_name'] ?>"
                                                data-email="<?= $user['email'] ?>"
                                                data-role="<?= $user['role'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-btn" 
                                                data-id="<?= $user['id'] ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="add_user.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="admin">Admin</option>
                                <option value="teacher">Teacher</option>
                                <option value="student">Student</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <!-- Similar structure to Add Modal, populated via JavaScript -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit Modal Handler
        document.getElementById('editUserModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const modal = this;
            
            modal.querySelector('#editFullName').value = button.dataset.name;
            modal.querySelector('#editEmail').value = button.dataset.email;
            modal.querySelector('#editRole').value = button.dataset.role;
            modal.querySelector('form').action = `edit_user.php?id=${button.dataset.id}`;
        });

        // Search Functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });

        // Delete Confirmation
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if(confirm('Are you sure you want to delete this user?')) {
                    window.location = `delete_user.php?id=${this.dataset.id}`;
                }
            });
        });
    </script>
    <div class="modal fade" id="resetPwdModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="user_id" id="resetUserId">
                <div class="modal-body">
                    <input type="password" name="new_password" class="form-control" 
                           placeholder="New Password" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Password Reset Handler
document.querySelectorAll('.reset-pwd-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('resetUserId').value = this.dataset.userid;
        new bootstrap.Modal('#resetPwdModal').show();
    });
});
</script>
</body>
</html>