<?php
require_once 'auth.php';

// Fetch classes and students
$classes = $pdo->query("SELECT id, class_name FROM classes")->fetchAll();
$students = $pdo->query("SELECT id, full_name FROM users WHERE role = 'student'")->fetchAll();

// Get enrolled students if class is selected
$enrolledStudents = [];
if (isset($_GET['class_id'])) {
    $classId = $_GET['class_id'];
    $stmt = $pdo->prepare("
        SELECT u.id, u.full_name 
        FROM users u
        JOIN class_students cs ON u.id = cs.student_id
        WHERE cs.class_id = ?
    ");
    $stmt->execute([$classId]);
    $enrolledStudents = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .student-list { max-height: 400px; overflow-y: auto; }
        .enrolled { background-color: #e8f5e9 !important; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Include sidebar -->
            
            <main class="col-md-10 p-4">
                <h2><i class="fas fa-user-graduate me-2"></i>Student Enrollment</h2>
                
                <!-- Class Selection -->
                <div class="card mb-4">
                    <div class="card-body">
                        <select id="classSelect" class="form-select">
                            <option value="">Select a Class</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>" 
                                    <?= isset($_GET['class_id']) && $_GET['class_id'] == $class['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($class['class_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Enrollment Interface -->
                <?php if (isset($_GET['class_id'])): ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Enrolled Students</div>
                            <div class="card-body student-list">
                                <div id="enrolledStudents">
                                    <?php foreach ($enrolledStudents as $student): ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2 enrolled">
                                            <span><?= htmlspecialchars($student['full_name']) ?></span>
                                            <button class="btn btn-sm btn-danger remove-btn" 
                                                    data-student-id="<?= $student['id'] ?>">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Available Students</div>
                            <div class="card-body student-list">
                                <input type="text" id="searchStudents" class="form-control mb-3" placeholder="Search...">
                                <div id="availableStudents">
                                    <?php foreach ($students as $student): ?>
                                        <?php if (!in_array($student['id'], array_column($enrolledStudents, 'id'))): ?>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span><?= htmlspecialchars($student['full_name']) ?></span>
                                                <button class="btn btn-sm btn-success enroll-btn" 
                                                        data-student-id="<?= $student['id'] ?>">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Class Selection Handler
        document.getElementById('classSelect').addEventListener('change', function() {
            window.location = `manage_enrollment.php?class_id=${this.value}`;
        });

        // Enroll Student
        document.querySelectorAll('.enroll-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const classId = <?= $_GET['class_id'] ?? 0 ?>;
                const studentId = this.dataset.studentId;

                try {
                    const response = await fetch(`enroll_student.php?class_id=${classId}&student_id=${studentId}`);
                    if (response.ok) {
                        location.reload(); // Refresh to update lists
                    }
                } catch (error) {
                    console.error('Enrollment failed:', error);
                }
            });
        });

        // Remove Student
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const classId = <?= $_GET['class_id'] ?? 0 ?>;
                const studentId = this.dataset.studentId;

                if (confirm('Remove student from class?')) {
                    try {
                        const response = await fetch(`remove_student.php?class_id=${classId}&student_id=${studentId}`);
                        if (response.ok) {
                            location.reload();
                        }
                    } catch (error) {
                        console.error('Removal failed:', error);
                    }
                }
            });
        });

        // Search Students
        document.getElementById('searchStudents').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('#availableStudents > div').forEach(div => {
                const name = div.textContent.toLowerCase();
                div.style.display = name.includes(term) ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>