<?php
require_once 'auth.php';

// Fetch all classes with teacher names
$stmt = $pdo->query("
    SELECT c.*, u.full_name AS teacher_name 
    FROM classes c
    LEFT JOIN users u ON c.teacher_id = u.id
");
$classes = $stmt->fetchAll();

// Fetch all teachers for dropdown
$teachers = $pdo->query("SELECT id, full_name FROM users WHERE role = 'teacher'")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Classes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .schedule-badge { background-color: #e9ecef; color: #495057; }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Include your sidebar -->
            
            <main class="col-md-10 p-4">
                <div class="d-flex justify-content-between mb-4">
                    <h2><i class="fas fa-chalkboard me-2"></i>Manage Classes</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                        <i class="fas fa-plus me-2"></i>Add Class
                    </button>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Class Name</th>
                                    <th>Teacher</th>
                                    <th>Schedule</th>
                                    <th>Room</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($classes as $class): ?>
                                <tr>
                                    <td><?= htmlspecialchars($class['class_name']) ?></td>
                                    <td>
                                        <?php if ($class['teacher_name']): ?>
                                            <span class="badge bg-primary">
                                                <?= htmlspecialchars($class['teacher_name']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="schedule-badge badge">
                                            <?= htmlspecialchars($class['schedule']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($class['room_number']) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-class" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editClassModal"
                                                data-classid="<?= $class['id'] ?>"
                                                data-classname="<?= $class['class_name'] ?>"
                                                data-teacher="<?= $class['teacher_id'] ?>"
                                                data-schedule="<?= $class['schedule'] ?>"
                                                data-room="<?= $class['room_number'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-class" 
                                                data-classid="<?= $class['id'] ?>">
                                            <i class="fas fa-trash"></i>
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

    <!-- Add Class Modal -->
    <div class="modal fade" id="addClassModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="add_class.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Class</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Class Name</label>
                            <input type="text" name="class_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Teacher</label>
                            <select name="teacher_id" class="form-select">
                                <option value="">Select Teacher</option>
                                <?php foreach ($teachers as $teacher): ?>
                                    <option value="<?= $teacher['id'] ?>">
                                        <?= htmlspecialchars($teacher['full_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Schedule (e.g., Mon/Wed 10:00-11:30)</label>
                            <input type="text" name="schedule" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Room Number</label>
                            <input type="text" name="room_number" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Class Modal (similar structure to Add Modal) -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit Class Modal Handler
        document.querySelectorAll('.edit-class').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = document.getElementById('editClassModal');
                modal.querySelector('[name="class_name"]').value = this.dataset.classname;
                modal.querySelector('[name="teacher_id"]').value = this.dataset.teacher;
                modal.querySelector('[name="schedule"]').value = this.dataset.schedule;
                modal.querySelector('[name="room_number"]').value = this.dataset.room;
                modal.querySelector('form').action = `edit_class.php?id=${this.dataset.classid}`;
            });
        });

        // Delete Confirmation
        document.querySelectorAll('.delete-class').forEach(btn => {
            btn.addEventListener('click', function() {
                if(confirm('Delete this class? Students enrollment will be preserved.')) {
                    window.location = `delete_class.php?id=${this.dataset.classid}`;
                }
            });
        });
    </script>
</body>
</html>