<?php
function calculateProgressBasedOnDays($createDate, $dueDate)
{
  $start = DateTime::createFromFormat('Y-m-d H:i:s', $createDate);
  $due = DateTime::createFromFormat('Y-m-d H:i:s', $dueDate);
  $now = new DateTime();
  $totalDuration = $start->diff($due)->days;
  if ($totalDuration <= 0) {
    echo "Error";
    return;
  }
  $elapsedDuration = $start->diff($now)->days;
  if ($elapsedDuration >= $totalDuration) {
    $progress = 100;
  }
  $progress = min(($elapsedDuration / $totalDuration) * 100, 100);

  return round($progress, 2);
}

?>

<h2>Dashboard Content</h2>
<div class="container mt-4">
  <h2 class="text-center mb-4">To-Do List</h2>
  <div class="py-4">

    <form method="post" action="/dashboard/filter" class="mb-3">
      <div class="row">
        <div class="col-md-2 my-2">
          <select name="status" class="form-select">
            <option value="all" <?= ($_GET['status'] ?? 'all') == 'all' ? 'selected' : '' ?>>All</option>
            <option value="in progress" <?= ($_GET['status'] ?? '') == 'in-progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="complete" <?= ($_GET['status'] ?? '') == 'complete' ? 'selected' : '' ?>>Complete</option>
            <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
          </select>
        </div>
        <div class="col-md-2 my-2">
          <div>
            <select class="form-select" id="category" name="category_id">
              <option value="" selected disabled>Choose Category</option>
              <option value="1">Personal</option>
              <option value="2">Work</option>
              <option value="3">Shopping</option>
              <option value="4">Health</option>
              <option value="5">Leisure</option>
              <option value="6">Learning</option>
            </select>
          </div>
        </div>
        <div class="col-md-2 my-2">
          <select class="form-select" id="priority" name="priority_level_id">
            <option value="" selected disabled>Choose priority</option>
            <option value="1">High</option>
            <option value="2">Medium</option>
            <option value="3">Low</option>
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100 mt-2">Filter</button>
        </div>
      </div>
    </form>

  </div>
  <!-- Task List -->
  <div class="card">
    <div class="card-header">
      Tasks
    </div>
    <ul class="list-group list-group-flush">
      <!-- Fake Tasks -->
      <?php foreach ($data as $items): ?>
        <li class="list-group-item">

          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1"><?php echo $items['title']; ?></h5>
              <small class="text-muted">Due Date: <?php echo date('M Y d', strtotime($items['due_date'])); ?></small>
            </div>
            <div class="row">
              <div class="col">
                <span class="badge <?php
                                    if ($items['status'] == 'pending') {
                                      echo "bg-secondary";
                                    }
                                    if ($items['status'] == 'in progress') {
                                      echo "bg-warning";
                                    }
                                    if ($items['status'] == 'completed') {
                                      echo "bg-success";
                                    }
                                    ?> text-dark"><?php echo $items['status']; ?></span>


              </div>
              <div class="col">
                <a class="btn btn-sm btn-primary" href="/dashboard/edit/<?php echo $items['id']; ?>">Edit</a>
              </div>
              <div class="col">
                <a class="btn btn-sm btn-danger" href="/dashboard/delete/<?php echo $items['id']; ?>">Delete</a>
              </div>
            </div>

          </div>
          <div class="progress mt-2" style="height: 20px;">
            <?php
            $progressStatus = calculateProgressBasedOnDays($items['created_at'], $items['due_date']);
            ?>
            <div class="progress-bar <?php
                                      switch ($items['status']) {
                                        case 'pending':
                                          echo "bg-secondary";
                                          break;
                                        case 'in progress':
                                          echo "bg-warning";
                                          break;
                                        case 'completed':
                                          echo "bg-success";
                                          break;
                                        default:
                                          echo "";
                                          break;
                                      } ?>" role="progressbar"
              style="width: <?php echo $progressStatus; ?>%;"
              aria-valuenow="<?php echo $progressStatus; ?>"
              aria-valuemin="0" aria-valuemax="100">
              <?php echo $progressStatus; ?>%
            </div>

          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>