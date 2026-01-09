<div class="row">
    <div class="col-md-7">
        <h4>Report Road Issue</h4>
        <form method="post" action="index.php?page=report" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= csrfToken(); ?>">
            <input type="hidden" name="action" value="create_issue">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Issue Type</label>
                <select name="issue_type" class="form-select" required>
                    <option value="">Select</option>
                    <option value="pothole">Pothole</option>
                    <option value="broken streetlight">Broken Streetlight</option>
                    <option value="water leakage">Water Leakage</option>
                    <option value="accident">Accident</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image (optional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="latitude" id="latitude" class="form-control" readonly required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="longitude" id="longitude" class="form-control" readonly required>
                </div>
            </div>
            <button class="btn btn-success">Submit Issue</button>
        </form>
    </div>
    <div class="col-md-5">
        <h5>Pick location</h5>
        <div id="map" style="height:400px;" class="rounded border"></div>
        <p class="small text-muted mt-2">Click on the map to capture coordinates.</p>
    </div>
</div>

