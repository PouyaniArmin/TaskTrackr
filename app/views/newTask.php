<div class="container">
    <div class="border-bottom">
        <h2>New Task</h2>
    </div>
    <div class="container pt-4">
        <form class="m-2 pt-2" method="post">
            <div class="row g-3">
                <div class="col">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
                <div class="col">
                    <label for="priority" class="form-label">Priority Level</label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="" selected disabled>Choose priority</option>
                        <option value="1">High</option>
                        <option value="2">Medium</option>
                        <option value="3">Low</option>
                    </select>
                </div>
                <div class="col">
                    <label for="due_date" class="form-label">Due Date</label>
                    <input type="text" class="form-control" id="due_date" name="due_date" placeholder="Select Date" required>
                </div>
            </div>
    </div>
    <div class="row g-3 mx-2">
        <div class="col">
            <div>
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="" selected disabled>Choose Status</option>
                    <option value="pending">Pending</option>
                    <option value="in progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>
        <div class="col">
            <div>
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
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
    </div>

    <div class="mb-3 my-4">
        <textarea class="form-control" name="description" id="exampleFormControlTextarea1" placeholder="Description" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<script>
    $('#due_date').datepicker({
        uiLibrary: 'bootstrap5'
    });
</script>