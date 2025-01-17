<div class="container">
    <div class="border-bottom">
        <h2>New Task</h2>
    </div>
    <div class="container pt-4">
        <form class="m-2 pt-2" method="post" action="/dashboard/updateTask">
            <input type="hidden" name="id" value="<?= $id?>">
            <div class="row g-3">
                <div class="col">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $title ?>">
                </div>
                <div class="col">
                    <label for="priority" class="form-label">Priority Level</label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="" disabled>Choose priority</option>
                        <option value="1" <?= ($priority_level_id == 1) ? 'selected' : '' ?>>High</option>
                        <option value="2" <?= ($priority_level_id == 2) ? 'selected' : '' ?>>Medium</option>
                        <option value="3" <?= ($priority_level_id == 3) ? 'selected' : '' ?>>Low</option>
                    </select>

                </div>
                <div class="col">
                    <label for="due_date" class="form-label">Due Date</label>
                    <input type="text" class="form-control" id="due_date" name="due_date" placeholder="Select Date" value="<?= $due_date ?>" required>
                </div>
            </div>
    </div>
    <div class="row g-3 mx-2">
        <div class="col">
            <div>
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="" disabled>Choose Status</option>
                    <option value="pending" <?= ($status === 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="in progress" <?= ($status === 'in progress') ? 'selected' : '' ?>>In Progress</option>
                    <option value="completed" <?= ($status === 'completed') ? 'selected' : '' ?>>Completed</option>
                </select>

            </div>
        </div>
        <div class="col">
            <div>
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="" disabled>Choose Category</option>
                    <option value="1" <?= ($category_id == 1) ? 'selected' : '' ?>>Personal</option>
                    <option value="2" <?= ($category_id == 2) ? 'selected' : '' ?>>Work</option>
                    <option value="3" <?= ($category_id == 3) ? 'selected' : '' ?>>Shopping</option>
                    <option value="4" <?= ($category_id == 4) ? 'selected' : '' ?>>Health</option>
                    <option value="5" <?= ($category_id == 5) ? 'selected' : '' ?>>Leisure</option>
                    <option value="6" <?= ($category_id == 6) ? 'selected' : '' ?>>Learning</option>
                </select>

            </div>
        </div>
    </div>

    <div class="mb-3 my-4">
        <textarea class="form-control" name="description" id="exampleFormControlTextarea1" placeholder="Description" rows="3"><?= $description?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<script>
    $('#due_date').datepicker({
        uiLibrary: 'bootstrap5'
    });
</script>